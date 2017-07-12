<?php

namespace AppBundle\Controller;

use DateTime;
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

        // Jour de generation du pdf
        $now = new DateTime('now');
        $dateStamp = $now->format('d-m-Y');
        $timestamp = $now->getTimestamp();
        $filename = 'VISAS_'.$dateStamp.'_Affaire'.$affaire->getNumeroAffaire().$timestamp.'.pdf';

        //Recuperation des donnees pour generer le pdf
        $pdfData = [
            'dateStamp'     => $dateStamp,
            'timestamp'     => $timestamp
        ];


        //Generation du pdf

        $snappy = $this->get('knp_snappy.pdf');
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
