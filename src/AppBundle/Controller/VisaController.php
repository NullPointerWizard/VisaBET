<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lots;
use AppBundle\Entity\Visas;
use AppBundle\Form\VisaFormType;
use AppBundle\Form\UploadFileFormType;
use AppBundle\Form\AjouterUtilisateurSurAffaireFormType;
use AppBundle\Entity\Documents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\AffaireFormType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LotFormType;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\ItemFormType;

/**
 *
 * @author NullPointerWizard
 *
 */
class VisaController extends Controller {

	/**
	 * Page permettant la connexion au service
	 *
	 * @Route("/connexion", name="connexion")
	 */
	public function showConnexion()
	{
		return $this->render('applicationVisa/connexion.html.twig');
	}

	/**
	 * Page permettant la visualisation de l'accueil, equivalent a la route affaires pour l'instant
	 *
	 * @Route("/Accueil", name="accueil")
	 */
	public function showAccueil(){
		return $this->redirectToRoute('affaires');
	}
	/**
	 * Page permettant la visualisation des affaires de l'utilisateur
	 *
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires", name="affaires")
	 */
	// public function showVueAffaires($nomOrganisme, $nomUtilisateur)
	// {
	//
	// 	$entityManager = $this->getDoctrine()->getManager();
	// 	$organisme = $entityManager->getRepository('AppBundle\Entity\Organismes')
	// 		->findOneBy(['nomOrganisme' => $nomOrganisme]);
	//
	// 	//Recuperation des affaires (concernant l'utilisateur - A IMPLEMENTER)
	// 	$listeAffairesUtilisateur =  $organisme->getAffaires();
	//
	// 	return $this->render('applicationVisa/accueil_utilisateur.html.twig',[
	// 			'nomOrganisme' 				=> $nomOrganisme,
	// 			'nomUtilisateur'			=> $nomUtilisateur,
	// 			'listeAffairesUtilisateur' 	=> $listeAffairesUtilisateur
	// 	]);
	// }

	/**
	 * Page permettant la visualisation des affaires de l'utilisateur
	 *
	 * @Route("/Affaires", name="affaires")
	 */
	public function showAffairesUt()
	{

		if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
			$this->addFlash('error','ACCES INTERDIT : Vous devez etre connecte ou n\'avez pas les droits.');
		    throw $this->createAccessDeniedException('GET OUT!');
		}

		//$this->denyAccessUnlessGranted('ROLE_USER');

		$utilisateur = $this->getUser();
		$organisme = $utilisateur->getIdOrganisme();

		//Recuperation des affaires (concernant l'utilisateur - A IMPLEMENTER)
		//$listeAffairesUtilisateur =  $organisme->getAffaires();
		$listeAffairesUtilisateur = $utilisateur->getListeAffaires();

		return $this->render('applicationVisa/accueil.html.twig',[
				'organisme' 				=> $organisme,
				'listeAffairesUtilisateur' 	=> $listeAffairesUtilisateur
		]);
	}

	/**
	 * Page de création d'une nouvelle affaire
	 *
	 * @Route("/Affaires/Nouvelle", name="creer_affaire")
	 */
	public function newAffaire(Request $request)
	{

		$form = $this->createForm(AffaireFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$nouvelleAffaire = $form->getData();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($nouvelleAffaire);
			$entityManager->flush();

			$this->addFlash('success', 'Affaire creee !');

			return $this->redirectToRoute('affaires');
		}

		return $this->render('applicationVisa/nouvelle_affaire.html.twig', [
				'affaireForm' => $form->createView()
		]);
	}

	/**
	 * Page de visualisation d'une affaire en details
	 *
	 * @Route("/Affaires/Affaire{numeroAffaire}", name="affaire_details")
	 */
	public function showAffaire($numeroAffaire, Request $request)
	{
		$utilisateur = $this->getUser();
		$listeItems = [];
		$listeVisas = [];
		$entityManager = $this->getDoctrine()->getManager();

		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire, 'idOrganisme'=> $utilisateur->getIdOrganisme()])
		;

		if(!$affaire)
		{
			throw $this->createNotFoundException('Erreur 404 not found: L\'affaire demandee est introuvable');
		}

		// Creation du formulaire pour un nouveau lot, on doit initaliser l'affaire avant afin de pouvoir faire la verification de numero de lot unique
		$nouveauLot = new Lots;
		$nouveauLot->setAffaire($affaire);
		$form = $this->createForm(LotFormType::class, $nouveauLot);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){

			$nouveauLot = $form->getData();

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($nouveauLot);
			$entityManager->flush();

			$this->addFlash('success', 'Lot cree ! (LOT '.$nouveauLot->getNumeroLot().' : '.$nouveauLot->getNomLot().')' );

			//redirection vers la meme url
			return $this->redirect($request->getUri());
		}

		// Creation du formulaire pour uploader des documents
		$filesForm = $this->createForm(UploadFileFormType::class, $nouveauDoc = new Documents );
		$filesForm->handleRequest($request);
		if ($filesForm->isSubmitted() && $filesForm->isValid()) {
			$nouveauDoc = $filesForm->getData();
			$nouveauDoc->upload($affaire);
			$nouveauDoc->setDateReception('now');
			$nouveauDoc->setDateLimiteVisa('now');
			$nouveauDoc->setIdAffaire($affaire);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($nouveauDoc);
			$entityManager->flush();
		}

		// Creation du formulaire pour ajouter un utilisateur a l'affaire
		$addUtilisateurForm = $this->createForm(AjouterUtilisateurSurAffaireFormType::class);
		$addUtilisateurForm->handleRequest($request);

		if ($addUtilisateurForm->isSubmitted() && $addUtilisateurForm->isValid()){

			$entityManager = $this->getDoctrine()->getManager();
			$data = $addUtilisateurForm->getData();

			foreach($data['utilisateurs']  as $utilisateurSupplementaire)
			{
				$affaire->addListeUtilisateur($utilisateurSupplementaire);
				$utilisateurSupplementaire->addAffaire($affaire);
				$entityManager->persist($utilisateurSupplementaire);

				$this->addFlash('success', 'Utilisateur '.$utilisateurSupplementaire.' ajoute ! ' );
			}
			$entityManager->persist($affaire);
			$entityManager->flush();

			//redirection vers la meme url
			return $this->redirect($request->getUri());
		}

		// On recupere dans la BDD les lots rattaches a l'affaire (l'allotissement) et on charge les items eventuels
		$allotissement = $affaire->getLots();

		if ($allotissement)
		{
			foreach ($allotissement as $lot)
			{
				$idLot = $lot->getIdLot();
				$listeItems[$idLot] = $entityManager->getRepository('AppBundle\Entity\Items')
					->findAllItems($lot)
				;
				foreach ($listeItems[$idLot] as $item)
				{
					$listeVisas[$item->getIdItem()] = $entityManager->getRepository('AppBundle\Entity\Visas')
						->findOneBy( array('idItem' => $item->getIdItem()) )
					;
				}
			}

		}

		//On recupere dans la BDD les documents rattaches a l'affaire
		$listeDocumentsAffaire = $affaire->getDocuments();
		$listeUtilisateursAffaire = $affaire->getListeUtilisateur();

		$data =
		[
				'affaire' 					=> $affaire,

				'listeUtilisateursAffaire'	=> $listeUtilisateursAffaire,
				'listeDocumentsAffaire' 	=> $listeDocumentsAffaire,
				'allotissement' 			=> $allotissement,
				'listeItems'				=> $listeItems,
				'listeVisas'				=> $listeVisas,
				'affaire'					=> $affaire,

				'lotForm' 					=> $form->createView(),
				'utilisateurForm'			=> $addUtilisateurForm->createView(),
				'filesForm'					=> $filesForm->createView()
		];
		return $this->render('applicationVisa/affaire_details.html.twig', $data);

	}

	/**
	 * Page de  gestion des items pour un lot (modification et creation d'items)
	 *
	 * @Route("/Affaires/Affaire{numeroAffaire}/Lot{numeroLot}", name="gestion_items")
	 */
	public function showGestionItems($numeroAffaire, $numeroLot, Request $request){

		$types = ['Plan','NDC','Materiel','Autre'];
		$utilisateur = $this->getUser();

		$entityManager = $this->getDoctrine()->getManager();
		$listeItems = new ArrayCollection();
		$listeVisas = new ArrayCollection();
		$listeRemarques =  new ArrayCollection();

		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire, 'idOrganisme'=> $utilisateur->getIdOrganisme()])
		;
		$lot = $entityManager->getRepository('AppBundle\Entity\Lots')
			->findOneBy(['numeroLot'=>$numeroLot, 'affaire' => $affaire])
		;

		//On recupere pour chaque type les items du lot et les visas correspondants
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

		//Creation du formulaire pour un nouvel item
		$form = $this->createForm(ItemFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$nouvelItem = $form->getData();
			$nouvelItem ->setIdLot( $lot ) ;
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($nouvelItem);
			$entityManager->flush();

			$this->addFlash('success', 'Item cree !');

			return $this->redirect($request->getUri());
		}



		$data =
		[
				'numeroAffaire'				=> $numeroAffaire,

				'lot' 						=> $lot,
				'affaire'					=> $affaire,

				'types'						=> $types,

				'listeItems'				=> $listeItems,
				'listeVisas'				=> $listeVisas,
				'listeRemarques'			=> $listeRemarques,

				'itemForm'					=> $form->createView()

		];
		return $this->render('applicationVisa/gestion_items.html.twig', $data);
	}


	/**
	 * Edition des remarques d'un visa
	 *
	 * @Route("/Affaires/Affaire{numeroAffaire}/Lot{numeroLot}/Item{idItem}", name="visa_remarques")
	 */
	public function showVisaRemarques($numeroAffaire, $numeroLot, $idItem, Request $request) {

		//On verifie que l'Utilisateur est autorise
		//1
			// if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
	        //     throw $this->createAccessDeniedException('GET OUT!');
	        // }
		//2
		$this->denyAccessUnlessGranted('ROLE_USER');


		$entityManager = $this->getDoctrine()->getManager();
		$item = $entityManager->getRepository('AppBundle\Entity\Items')
			->findOneBy(['idItem'=>$idItem]);
		$nouveauVisa = new Visas;

		//Definition des formulaires
		$form = $this->createForm(VisaFormType::class, $nouveauVisa);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$nouveauVisa = $form->getData();
			$nouveauVisa->setIdItem($item);
			$nouveauVisa->setVersion();
			$nouveauVisa->setDateEmission('now');
			$entityManager = $this->getDoctrine()->getManager();

			$entityManager->persist($nouveauVisa);
			$noRemarque = 1;
			foreach($nouveauVisa->getRemarques() as $remarque)
			{
				$remarque->setIdVisa($nouveauVisa);
				$remarque->setNoRemarque($noRemarque);
				$entityManager->persist($remarque);
				$noRemarque++;
			}
			$entityManager->flush();

			$this->addFlash(
				'success',
				 sprintf('(%s %s) Vous avez emis le visa %s pour: %s',
				 	$this->getUser()->getPrenom(),
					$this->getUser()->getNom(),
					$nouveauVisa->getVersion(),
					$item->getNomItem()
				)
			);

			return $this->redirectToRoute('gestion_items', array('numeroAffaire' => $numeroAffaire, 'numeroLot' => $numeroLot));
		}


		$data =
		[
				'numeroAffaire'				=> $numeroAffaire,

				'item'						=> $item,
				'form'						=> $form->createView()
		];
		return $this->render ( 'applicationVisa/nouveau_visa.html.twig', $data );
	}

	/**
	 * Page en construction
	 *
	 * @Route("/Travaux", name="travaux")
	 */
	public function showTravaux() {
		return $this->render ( 'applicationVisa/travaux.html.twig' );
	}



}
