<?php

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Entity\FicheVisa;
use AppBundle\Form\AjouterDocumentsFicheVisaFormType;
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
    *  "/Affaires/Affaire{numeroAffaire}/Lot{numeroLot}/NouvelleFiche",
    *  name="pdf_generator"
    * )
    */
    public function showPdfGenerator($numeroAffaire, $numeroLot, Request $request)
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
        $numeroFiche = 2;
        $fiche = new FicheVisa();
        $fiche->setLot($lot);
        $fiche->setNumeroFiche($numeroFiche);
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

        // Ajout de documents dans la fiche
        $addDocumentsForm = $this->createForm(AjouterDocumentsFicheVisaFormType::class, array('lot'=> $lot) );
        $addDocumentsForm->handleRequest($request);
        if ($addDocumentsForm->isSubmitted() && $addDocumentsForm->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $data = $addDocumentsForm->getData();

            foreach($data['documents']  as $document)
            {
                $fiche->addDocuments($document);
                $document->setFiche($fiche);
                $entityManager->persist($document);

                $this->addFlash('success', $document.' ajoute a la fiche ! ' );
            }
            $entityManager->persist($fiche);
            $entityManager->flush();

            //redirection vers la meme url
            return $this->redirect($request->getUri());
        }

        $data =[
            'affaire'           => $affaire,
            'lot'               => $lot,
            'listeDiffusion'    => $listeDiffusion,
            'numeroFiche'       => $numeroFiche,

            'addContactsForm'    => $addContactsForm->createView(),
            'addDocumentsForm'   => $addDocumentsForm->createView()
        ];
        return $this->render(
            'applicationVisa/pdf_generator.html.twig',
            $data
         );
    }



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
            'applicationVisa/pdf_view.html.twig',
            $pdfData
        );
        $filename = 'VISAS_LOT'.$numeroLot.'_Fiche'.$numeroFiche.'_au_'.$dateStamp.'_Affaire'.$affaire->getNumeroAffaire().'_TS'.$timestamp.'.pdf';
        $path = './'.'affaires/'.$affaire->getIdOrganisme()->getFolderName().'/'.$affaire->getFolderName().'/Lot_'.$lot->getNumeroLot().'/'.'visas/'.$filename ;
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('header-html', $headerHtml);
        $snappy->setOption('footer-html', $footerHtml);
        $snappy->generateFromHtml(
            $html,
            $path
        );

        // MAJ des infos de la fiche dans la BDD
        $fiche->setDate($now);
        $fiche->setFilename($filename);
        $fiche->setPath($path);
        $entityManager->persist($fiche);
        $entityManager->flush();


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
