<?php

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Form\AjouterContactListeDiffusionFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;

class PDFController extends Controller
{


    /**
    * Page permettant la creation des fiches Visas
    * @Route(
    *  "/Affaires/Affaire{numeroAffaire}/Lot{numeroLot}/FicheVisa",
    *  name="pdf_generator"
    * )
    */
    public function showPdfGenerator($numeroAffaire, $numeroLot,Request $request)
    {
        $utilisateur = $this->getUser();
		$entityManager = $this->getDoctrine()->getManager();
		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire, 'idOrganisme'=> $utilisateur->getIdOrganisme()])
		;
        $organisme = $utilisateur->getIdOrganisme();
        $lot = $entityManager->getRepository('AppBundle\Entity\Lots')
			->findOneBy(['numeroLot'=>$numeroLot, 'affaire' => $affaire])
		;
        $listeDiffusion = $lot->getListeDiffusion();

        // Ajout d'utilisateur dans la liste de diffusion
        $addContactsForm = $this->createForm(AjouterContactListeDiffusionFormType::class);
		$addContactsForm->handleRequest($request);

		if ($addContactsForm->isSubmitted() && $addContactsForm->isValid()){

			$entityManager = $this->getDoctrine()->getManager();
			$data = $addContactsForm->getData();

			foreach($data['contacts']  as $contact)
			{
				$lot->addListeDiffusion($contact);
				$contact->addListeLots($lot);
				$entityManager->persist($contact);

				$this->addFlash('success', $contact.' ajoute a la liste de diffusion ! ' );
			}
			$entityManager->persist($lot);
			$entityManager->flush();

			//redirection vers la meme url
			return $this->redirect($request->getUri());
		}

        $data =[
            'affaire'           => $affaire,
            'lot'               => $lot,
            'listeDiffusion'    => $listeDiffusion,

            'addContactsForm'    => $addContactsForm->createView()
        ];
        return $this->render(
            'applicationVisa/pdf_generator.html.twig',
            $data
         );
    }



    /**
    * @Route(
    *  "/Affaires/Affaire{numeroAffaire}/Lot{numeroLot}/FicheVisa/pdf",
    *  name="pdf_file"
    * )
    */
    public function generatePdfAction($numeroAffaire,$numeroLot)
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
        $listeDiffusion = $lot->getListeDiffusion();
        $listeDocuments = $lot->getDocuments();

        foreach($types as $type){
			$listeItems[$type] = $entityManager->getRepository('AppBundle\Entity\Items')
				->findAllItemsWhereType($lot,$type);

			foreach($listeItems[$type] as $item)
			{
				$listeVisas[$item->getIdItem()] = $entityManager->getRepository('AppBundle\Entity\Visas')
                    ->findOneBy( array(
                        'idItem' 	=> $item->getIdItem(),
                        'version'	=> $item->getVisasLastVersion()
                    ))
                ;
				foreach ($listeVisas as $visa)
				{
					$listeRemarques[$visa->getIdVisa()] = $visa->getRemarques();
				}
			}
		}

        //  dump($listeItems);
        //  dump($listeVisas);
        //  dump($listeRemarques);
        //  die;

        // Jour de generation du pdf
        $now = new DateTime('now');
        $dateStamp = $now->format('d-m-Y');
        $timestamp = $now->getTimestamp();
        $filename = 'VISAS_'.$dateStamp.'_Affaire'.$affaire->getNumeroAffaire().'_TS'.$timestamp.'.pdf';

        // Donnees pour la generation du pdf
        $pdfData = [
            'dateStamp'         => $dateStamp,
            'timestamp'         => $timestamp,
            'organisme'         => $organisme,
            'affaire'           => $affaire,
            'lot'               => $lot,
            'types'             => $types,

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
                'lot'               => $lot
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
            'applicationVisa/pdf_view.html.twig',
            $pdfData
        );
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('header-html', $headerHtml);
        $snappy->setOption('footer-html', $footerHtml);
        $snappy->generateFromHtml(
            $html,
            './'.'affaires/'.$affaire->getIdOrganisme()->getFolderName().'/'.$affaire->getFolderName().'/'.'visas/'.$filename
        );
        //$this->addFlash('success', 'PDF '.$filename.' genere ! ' );

        $data = [

        ];

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="'.$filename.'"'
            )
        );
    }
}
