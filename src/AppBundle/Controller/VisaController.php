<?php

namespace AppBundle\Controller;

use DateTime;
use DateInterval;
use AppBundle\Entity\Lots;
use AppBundle\Entity\Visas;
use AppBundle\Entity\Contact;
use AppBundle\Entity\FicheVisa;
use AppBundle\Entity\Organismes;
use AppBundle\Entity\TypesRemarque;
use AppBundle\Form\RoleFormType;
use AppBundle\Form\VisaFormType;
use AppBundle\Form\FicheFormType;
use AppBundle\Form\NomLotFormType;
use AppBundle\Form\ContactFormType;
use AppBundle\Form\ChoixDocFormType;
use AppBundle\Form\OrganismesFormType;
use AppBundle\Form\UploadFileFormType;
use AppBundle\Form\EmissionAvisFormType;
use AppBundle\Form\TypesRemarqueFormType;
use AppBundle\Form\AjouterDocumentsFicheVisaFormType;
use AppBundle\Form\AjouterContactListeDiffusionFormType;
use AppBundle\Form\AjouterUtilisateurSurAffaireFormType;
use AppBundle\Entity\Documents;
use AppBundle\Entity\Affaires;
use AppBundle\Entity\Items;
use AppBundle\Entity\NomsLots;
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
	 * Tableau
	 *
	 * @Route("/TableauDeBord/VueGlobale", name="tableau_global")
	 */
	public function showTableauGlobal()
	{
		$listeDocuments = [];

		$data = [
			'listeDocuments'		=> $listeDocuments
		];
		return $this->render('applicationVisa/tableau_global.html.twig', $data);
	}

	/**
	 * Tableau permettant d'emettre rapidement des visas et des avis
	 *
	 * @Route("/TableauDeBord/EmissionAvis", name="tableau_emission_avis")
	 */
	public function showTableauEmissionAvis(Request $request)
	{
		$form = $this->createForm(ChoixDocFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
		}

		$data = [
			'form' 					=> $form->createView(),
		];
		//return $this->render('applicationVisa/tableau_emission_avis.html.twig', $data);
		return $this->render('applicationVisa/travaux.html.twig', $data);
	}

	/**
	 * Tableau permettant d'emettre rapidement des visas et des avis
	 *
	 * @Route("/TableauDeBord/EmissionAvis/Document{idDocument}", name="tableau_emission_avis_pour_doc")
	 */
	public function showTableauEmissionAvisPourDoc($idDocument, Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$document = $entityManager->getRepository('AppBundle\Entity\Documents')
			->findOneBy(['idDocument'=>$idDocument])
		;
		$lot = $document->getLot();
		$affaire = $document->getIdAffaire();
		$form = $this->createForm(EmissionAvisFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
		}

		$data = [
			'document'				=> $document,
			'lot'					=> $lot,
			'affaire'				=> $affaire,

			'form' 					=> $form->createView(),
		];
		return $this->render('applicationVisa/tableau_emission_avis.html.twig', $data);
	}

	/**
	 * Page permettant la visualisation des affaires de l'utilisateur
	 *
	 * @Route("/Affaires", name="affaires")
	 */
	public function showAffairesUtilisateur()
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
		$utilisateur = $this->getUser();
		$organisme = $utilisateur->getIdOrganisme();

		$nouvelleAffaire = new Affaires;
		$nouvelleAffaire->addListeUtilisateur($utilisateur);
		$nouvelleAffaire->setIdOrganisme($organisme);
		$nouvelleAffaire->setYear( (new DateTime('now'))->format('Y') );
		$nouvelleAffaire->setDateButoir((new DateTime('now'))->add(new DateInterval('P21D')));
		$nouvelleAffaire->setTravailAEffectuer('Créer les items importants');
		$form = $this->createForm(AffaireFormType::class, $nouvelleAffaire);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){

			$utilisateur->addAffaire($nouvelleAffaire);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($utilisateur);
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
		$nouveauDoc = new Documents;
		$nouveauDoc->setIdAffaire($affaire);
		$nouveauDoc->setDateDocument('now');
		$nouveauDoc->setDateReception('now');
		$nouveauDoc->setDateLimiteVisa( (new DateTime('now'))->add(new DateInterval('P7D')) );
		$filesForm = $this->createForm(UploadFileFormType::class, $nouveauDoc);
		$filesForm->handleRequest($request);
		if ($filesForm->isSubmitted() && $filesForm->isValid()) {

			$nouveauDoc = $filesForm->getData();
			$nouveauDoc->upload();

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($nouveauDoc);
			$entityManager->flush();

			$this->addFlash('success', 'Document '.$nouveauDoc.' ajoute ! ' );

			//redirection vers la meme url
			return $this->redirect($request->getUri());
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
						->findOneBy( array(
							'idItem' 	=> $item->getIdItem(),
							'version'	=> $item->getVisasLastVersion()
						))
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
		$allotissement= $affaire->getLots();

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
		$nouvelItem = new Items();
		$form = $this->createForm(ItemFormType::class, $nouvelItem);
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
				'allotissement'				=> $allotissement,

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

		$utilisateur = $this->getUser();

		$entityManager = $this->getDoctrine()->getManager();
		$item = $entityManager->getRepository('AppBundle\Entity\Items')
			->findOneBy(['idItem'=>$idItem]);
		$nouveauVisa = new Visas;
		$nouveauVisa->setVisePar($utilisateur);

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
	 * Page permettant de voir les fiches du lot
	 *
	 * @Route("/Affaires/Affaire{numeroAffaire}/Lot{numeroLot}/Fiches", name="fiches")
	 */
	public function showFiches($numeroAffaire, $numeroLot, Request $request)
	{
		$types = ['Plan','NDC','Materiel','Autre'];
		$utilisateur = $this->getUser();

		$entityManager = $this->getDoctrine()->getManager();

		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire, 'idOrganisme'=> $utilisateur->getIdOrganisme()])
		;
		$lot = $entityManager->getRepository('AppBundle\Entity\Lots')
			->findOneBy(['numeroLot'=>$numeroLot, 'affaire' => $affaire])
		;
		$listeDiffusion = $lot->getListeDiffusion();
		$listeFiches = $lot->getFiches();
		$allotissement= $affaire->getLots();

		// Formulaire nouvelle fiche
		$nouvelleFiche = new FicheVisa();
		$nouvelleFiche->setLot($lot);
		$nouvelleFiche->setNumeroFiche(0);
		$ficheForm = $this->createForm(FicheFormType::class, $nouvelleFiche);
		$ficheForm->handleRequest($request);
		if ( $ficheForm->isSubmitted() && $ficheForm->isValid() ){

			$entityManager->persist($nouvelleFiche);
			$entityManager->flush();
			$this->addFlash(
				'success',
				 sprintf(
					'Nouvelle fiche : '.$nouvelleFiche
				)
			);
			return $this->redirect($request->getUri());
		}

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
			$fiche = $data['fiche'];

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

		$data =
		[
			'lot' 						=> $lot,
			'affaire'					=> $affaire,
			'listeFiches'				=> $listeFiches,
			'listeDiffusion'   			=> $listeDiffusion,
			'allotissement'				=> $allotissement,

			'ficheForm' 				=> $ficheForm->createView(),
			'addDocumentsForm'  		=> $addDocumentsForm->createView(),
			'addContactsForm'   		=> $addContactsForm->createView(),
		];
		return $this->render('applicationVisa/gestion_fiches.html.twig', $data);
	}

	/**
	 * Page gestion des tables utilitaires
	 *
	 * @Route("/Gestion", name="gestion")
	 */
	public function showGestion(Request $request)
	{
		return $this->render ( 'applicationVisa/gestion.html.twig');
	}

	/**
	 * Page permettant de voir les contacts disponibles
	 *
	 * @Route("/Gestion/CarnetAdresses", name="gestion_carnet")
	 */
	public function showCarnetAdresses(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$listeContacts = $entityManager->getRepository('AppBundle\Entity\Contact')
			->findAll();

		$nouveauContact = new Contact();
		$form = $this->createForm(ContactFormType::class, $nouveauContact);
		$form->handleRequest($request);

		if ( $form->isSubmitted() && $form->isValid() ){
			$entityManager->persist($nouveauContact);
			$entityManager->flush();

			$this->addFlash(
				'success',
				 sprintf(
					'Nouveau contact : '.$nouveauContact
				)
			);

			return $this->redirect($request->getUri());
		}

		$data =
		[
				'listeContacts'				=> $listeContacts,

				'form'						=> $form->createView()

		];
		return $this->render('applicationVisa/gestion_carnet_adresses.html.twig', $data);
	}

	/**
	 * Page gestion des tables secondaires
	 *
	 * @Route("/Gestion/RoleContacts", name="gestion_role")
	 */
	public function showGestionRole(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$listeRole = $entityManager->getRepository('AppBundle\Entity\RoleContact')
			->findAll();

		$roleForm = $this->createForm(RoleFormType::class);
		$roleForm->handleRequest($request);

		if ( $roleForm->isSubmitted() && $roleForm->isValid() ){
			$nouveauRole = $roleForm->getData();

			$entityManager->persist($nouveauRole);
			$entityManager->flush();

			$this->addFlash(
				'success',
				 sprintf(
					'Nouveau role : '.$nouveauRole
				)
			);

			return $this->redirect($request->getUri());
		}


		$data =
		[
				'listeRole'	=> $listeRole,

				'roleForm'	=> $roleForm->createView()
		];
		return $this->render ( 'applicationVisa/gestion_role.html.twig', $data);
	}

	/**
	 * Page permettant de voir les noms de lot disponibles
	 *
	 * @Route("/Gestion/NomsLots", name="gestion_noms_lots")
	 */
	public function showNomsLots(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$listeNomsLot = $entityManager->getRepository('AppBundle\Entity\NomsLots')
			->findAll();

		$nouveauNomLot = new NomsLots();
		$form = $this->createForm(NomLotFormType::class, $nouveauNomLot);
		$form->handleRequest($request);

		if ( $form->isSubmitted() && $form->isValid() ){
			$entityManager->persist($nouveauNomLot);
			$entityManager->flush();

			$this->addFlash(
				'success',
				 sprintf(
					'Nouveau nom de lot: '.$nouveauNomLot
				)
			);

			return $this->redirect($request->getUri());
		}

		$data =
		[
				'listeNomsLot'				=> $listeNomsLot,

				'form'						=> $form->createView()

		];
		return $this->render('applicationVisa/gestion_noms_lots.html.twig', $data);
	}

	/**
	 * Page permettant de voir les organismes disponibles
	 *
	 * @Route("/Gestion/Organismes", name="gestion_organismes")
	 */
	public function showGestionOrganismes(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$listeOrganismes = $entityManager->getRepository('AppBundle\Entity\Organismes')
			->findAll();

		$nouvelOrganisme = new Organismes();
		$form = $this->createForm(OrganismesFormType::class, $nouvelOrganisme);
		$form->handleRequest($request);

		if ( $form->isSubmitted() && $form->isValid() ){
			$entityManager->persist($nouvelOrganisme);
			$entityManager->flush();

			$this->addFlash(
				'success',
				 sprintf(
					'Nouvel organisme: '.$nouvelOrganisme
				)
			);

			return $this->redirect($request->getUri());
		}

		$data =
		[
				'listeOrganismes'			=> $listeOrganismes,

				'form'						=> $form->createView()

		];
		return $this->render('applicationVisa/gestion_organismes.html.twig', $data);
	}

	/**
	 * Page permettant de voir les types de remarques disponibles
	 *
	 * @Route("/Gestion/TypesRemarques", name="gestion_types_remarques")
	 */
	public function showGestionTypesRemarques(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$listeTypesRemarques = $entityManager->getRepository('AppBundle\Entity\TypesRemarque')
			->findAll();

		$nouveauType = new TypesRemarque();
		$form = $this->createForm(TypesRemarqueFormType::class, $nouveauType);
		$form->handleRequest($request);

		if ( $form->isSubmitted() && $form->isValid() ){
			$entityManager->persist($nouveauType);
			$entityManager->flush();

			$this->addFlash(
				'success',
				 sprintf(
					'Nouvel type: '.$nouveauType
				)
			);

			return $this->redirect($request->getUri());
		}

		$data =
		[
				'listeTypesRemarques'		=> $listeTypesRemarques,

				'form'						=> $form->createView()

		];
		return $this->render('applicationVisa/gestion_types_remarques.html.twig', $data);
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
