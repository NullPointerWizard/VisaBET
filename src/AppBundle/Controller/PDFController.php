<?php

namespace AppBundle\Controller;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PDFController extends Controller
{
    /**
    * @Route(
    *  "/Affaires/Affaire{numeroAffaire}/pdf",
    *  name="generate_pdf"
    * )
    */
    public function generatePdfAction($numeroAffaire)
    {
        $utilisateur = $this->getUser();
		$entityManager = $this->getDoctrine()->getManager();
		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire, 'idOrganisme'=> $utilisateur->getIdOrganisme()])
		;
        $organisme = $utilisateur->getIdOrganisme();

        // Recuperation des donnees pour generer le pdf
        $types = ['Plan','NDC','Materiel','Autre'];
        $listeDocuments = new ArrayCollection();
        $listeItems = new ArrayCollection();
		$listeVisas = new ArrayCollection();
		$listeRemarques =  new ArrayCollection();

        $numeroLot = '1';
        $lot = $entityManager->getRepository('AppBundle\Entity\Lots')
			->findOneBy(['numeroLot'=>$numeroLot, 'affaire' => $affaire])
		;
        $listeDocuments = $lot->getDocuments();

        foreach($types as $type){
			$listeItems[$type] = $entityManager->getRepository('AppBundle\Entity\Items')
				->findAllItemsWhereType($lot,$type);

			foreach($listeItems[$type] as $item)
			{
				$listeVisas[$item->getIdItem()] = $item->getVisas();
				foreach ($listeVisas[$item->getIdItem()] as $visa)
				{
					$listeRemarques[$visa->getIdVisa()] = $visa->getRemarques();
				}
			}
		}

        // dump($listeItems);
        // dump($listeVisas);
        // dump($listeRemarques);
        // die;

        // Jour de generation du pdf
        $now = new DateTime('now');
        $dateStamp = $now->format('d-m-Y');
        $timestamp = $now->getTimestamp();
        $filename = 'VISAS_'.$dateStamp.'_Affaire'.$affaire->getNumeroAffaire().$timestamp.'.pdf';

        // Donnees pour la generation du pdf
        $pdfData = [
            'dateStamp'         => $dateStamp,
            'timestamp'         => $timestamp,
            'organisme'         => $organisme,
            'affaire'           => $affaire,
            'lot'               => $lot,
            'types'             => $types,

            'listeDocuments'    => $listeDocuments,
            'listeItems'        => $listeItems,
            'listeVisas'        => $listeVisas,
            'listeRemarques'    => $listeRemarques
        ];


        //Generation du pdf
        $footerHtml = $this->renderView(
            'applicationVisa/pdf_footer.html.twig',
            [
                'dateStamp'      => $dateStamp,
                'timestamp'      => $timestamp
            ]
        );


        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('footer-html', $footerHtml);
        $snappy->generateFromHtml(
            $this->renderView(
                'applicationVisa/pdf_view.html.twig',
                $pdfData
            ),
            './'.'affaires/'.$affaire->getIdOrganisme()->getFolderName().'/'.$affaire->getFolderName().'/'.'visas/'.$filename
        );
        $this->addFlash('success', 'PDF '.$filename.' genere ! ' );

        $data = [

        ];
        return $this->render('applicationVisa/travaux.html.twig', $data);
    }
}
