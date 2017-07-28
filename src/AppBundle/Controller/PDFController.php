<?php

namespace AppBundle\Controller;

require dirname(dirname(dirname(dirname(__FILE__)))).'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

use DateTime;
use AppBundle\Entity\FicheVisa;
use AppBundle\Form\AjouterDocumentsFicheVisaFormType;
use AppBundle\Form\AjouterContactListeDiffusionFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PDFController extends Controller
{
    /**
    * @Route(
    *  "/Affaires/Affaire{numeroAffaire}/Lot{numeroLot}/Fiches/Fiche{numeroFiche}/pdf",
    *  name="pdf_file"
    * )
    */
    public function generatePdfAction($numeroAffaire, $numeroLot, $numeroFiche)
    {
        $types = ['Plan','NDC','Materiel','Autre'];
        $listeDocuments = [];
        $listeItems = [];
		$listeVisas = [];
		$listeRemarques =  [];
        $listeDiffusion = [];

        $utilisateur = $this->getUser();
		$entityManager = $this->getDoctrine()->getManager();
		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire, 'idOrganisme'=> $utilisateur->getIdOrganisme()])
		;
        $organisme = $utilisateur->getIdOrganisme();

        // Recuperation des donnees pour generer le pdf
        $lot = $entityManager->getRepository('AppBundle\Entity\Lots')
			->findOneBy(['numeroLot'=>$numeroLot, 'affaire' => $affaire])
		;
        $fiche =  $entityManager->getRepository('AppBundle\Entity\FicheVisa')
			->findOneBy(['numeroFiche'=>$numeroFiche, 'lot' => $lot])
		;
        $listeDiffusion = $lot->getListeDiffusion();
        $listeDocuments = $fiche->getDocuments();
        foreach($listeDocuments as $document){
            $listeVisas[$document->getIdDocument()] = $document->getVisas();
            foreach($listeVisas[$document->getIdDocument()] as $visa)
            {
                $listeRemarques[$visa->getIdVisa()] = $visa->getRemarques();
            }
        }

        // Jour de generation du pdf
        $now = new DateTime('now');
        $dateStamp = $now->format('d-m-Y');
        $timestamp = $now->getTimestamp();

        // Donnees pour la generation du pdf
        $pdfData = [
            'dateStamp'         => $dateStamp,
            'timestamp'         => $timestamp,
            'organisme'         => $organisme,
            'affaire'           => $affaire,
            'lot'               => $lot,
            'types'             => $types,
            'fiche'             => $fiche,

            'listeDiffusion'    => $listeDiffusion,
            'listeDocuments'    => $listeDocuments,
            'listeItems'        => $listeItems,
            'listeVisas'        => $listeVisas,
            'listeRemarques'    => $listeRemarques
        ];


        //Generation du pdf
        $headerHtml = $this->renderView(
            'applicationVisa/pdf_header.html.twig',
            [
                'dateStamp'         => $dateStamp,
                'timestamp'         => $timestamp,
                'affaire'           => $affaire,
                'lot'               => $lot,
                'fiche'             => $fiche,
            ]

        );
        $footerHtml = $this->renderView(
            'applicationVisa/pdf_footer.html.twig',
            [
                'dateStamp'      => $dateStamp,
                'timestamp'      => $timestamp,
                'organisme'      => $organisme,
            ]
        );
        $html = $this->renderView(
            //'applicationVisa/pdf_view.html.twig',
            'pdf/pdf_view_html2pdf.html.twig',
            $pdfData
        );
        $filename = 'VISAS_LOT'.$numeroLot.'_Fiche'.$numeroFiche.'_au_'.$dateStamp.'_Affaire'.$affaire->getNumeroAffaire().'_TS'.$timestamp.'.pdf';
        $path = './'.'affaires/'.$affaire->getIdOrganisme()->getFolderName().'/'.$affaire->getFolderName().'/Lot_'.$lot->getNumeroLot().'/'.'visas/'.$filename ;
        // $snappy = $this->get('knp_snappy.pdf');
        // $snappy->setOption('header-html', $headerHtml);
        // $snappy->setOption('footer-html', $footerHtml);
        // $snappy->generateFromHtml(
        //     $html,
        //     $path
        // );

        $html2pdf = $this->get('app.html2pdf');
        $html2pdf->create();
        //return $html2pdf->generatePdf($html,'test');
        return new Response(
            $html2pdf->generatePdf($html,$filename),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="'.$filename.'"'
            )
        );

        //UTILISATATION DE SNAPPY avec WKHTMLTOPDF
            // MAJ des infos de la fiche dans la BDD
        // $fiche->setDate($now);
        // $fiche->setFilename($filename);
        // $fiche->setPath($path);
        // $entityManager->persist($fiche);
        // $entityManager->flush();
        //
        //
        // return new Response(
        //     $snappy->getOutputFromHtml($html),
        //     200,
        //     array(
        //         'Content-Type'          => 'application/pdf',
        //         'Content-Disposition'   => 'attachment; filename="'.$filename.'"'
        //     )
        // );
    }
}
