<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Visas;
use AppBundle\Form\VisaFormType;
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
	 * Page permettant la visualisation des affaires de l'utilisateur
	 *
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires", name="affaires")
	 */
	public function showVueAffaires($nomOrganisme, $nomUtilisateur)
	{

		$entityManager = $this->getDoctrine()->getManager();
		$organisme = $entityManager->getRepository('AppBundle\Entity\Organismes')
			->findOneBy(['nomOrganisme' => $nomOrganisme]);

		//Recuperation des affaires (concernant l'utilisateur - A IMPLEMENTER)
		$listeAffairesUtilisateur =  $organisme->getAffaires();

		return $this->render('applicationVisa/accueil_utilisateur.html.twig',[
				'nomOrganisme' 				=> $nomOrganisme,
				'nomUtilisateur'			=> $nomUtilisateur,
				'listeAffairesUtilisateur' 	=> $listeAffairesUtilisateur
		]);
	}

	/**
	 * Page de création d'une nouvelle affaire
	 *
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires/Nouvelle", name="creer_affaire")
	 */
	public function newAffaire($nomOrganisme,$nomUtilisateur, Request $request)
	{
// 		$affaire = new Affaires();
// 		$affaire->setNomAffaire('L\'affaire du si�cle');
// 		$entityManager = $this->getDoctrine()->getManager();
// 		$entityManager->persist($affaire);
// 		$entityManager->flush();

		$form = $this->createForm(AffaireFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$nouvelleAffaire = $form->getData();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($nouvelleAffaire);
			$entityManager->flush();

			$this->addFlash('success', 'Affaire créée !');

			return $this->redirectToRoute('login');
		}

		return $this->render('applicationVisa/nouvelle_affaire.html.twig', [
				'affaireForm' => $form->createView()
		]);
	}

	/**
	 * Page de visualisation d'une affaire en d�tails
	 *
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires/Affaire{numeroAffaire}/ID{idAffaire}", name="affaire_details")
	 */
	public function showAffaire($nomOrganisme, $nomUtilisateur, $numeroAffaire, $idAffaire, Request $request)
	{
		$listeItems = [];
		$listeVisas = [];
		$entityManager = $this->getDoctrine()->getManager();

		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire])
		;

		if(!$affaire)
		{
			throw $this->createNotFoundException('Erreur 404 not found: L\'affaire demandee est introuvable');
		}

		//Creation du formulaire pour un nouveau lot
		$form = $this->createForm(LotFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$nouveauLot = $form->getData();
			$nouveauLot->setIdAffaire($affaire) ;
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($nouveauLot);
			$entityManager->flush();

			$this->addFlash('success', 'Lot cree ! (LOT N'.$nouveauLot->getNumeroLot().' '.$nouveauLot->getNomLot().')' );

			//return $this->redirectToRoute('visa_login');
		}

		//On r�cup�re dans la BDD les lots rattach�s � l'affaire (l'allotissement) et on charge les items eventuels
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

		//On r�cup�re dans la BDD les documents rattach�s � l'affaire
		$listeDocumentsAffaire = $affaire->getDocuments();

		$data =
		[
				'nomOrganisme'				=> $nomOrganisme,
				'nomUtilisateur'			=> $nomUtilisateur,
				'numeroAffaire'				=> $numeroAffaire,
				'idAffaire' 				=> $idAffaire,

				'listeDocumentsAffaire' 	=> $listeDocumentsAffaire,
				'allotissement' 			=> $allotissement,
				'listeItems'				=> $listeItems,
				'listeVisas'				=> $listeVisas,
				'affaire'					=> $affaire,

				'lotForm' 					=> $form->createView()
		];
		return $this->render('applicationVisa/affaire_details.html.twig', $data);

	}

	/**
	 * Page de  gestion des items pour un lot (modification et cr�ation d'items)
	 *
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires/Affaire{numeroAffaire}/ID{idAffaire}/Lot{idLot}", name="gestion_items")
	 */
	public function showGestionItems($nomOrganisme, $nomUtilisateur, $numeroAffaire, $idAffaire, $idLot, Request $request){

		$types = ['Plan','NDC','Materiel','Autre'];

		$entityManager = $this->getDoctrine()->getManager();
		$listeItems = new ArrayCollection();
		$listeVisas = new ArrayCollection();
		$listeRemarques =  new ArrayCollection();
		$affaire = $entityManager->getRepository ( 'AppBundle\Entity\Affaires' )
			->findOneBy ([ 'idAffaire' => $idAffaire ])
		;
		$lot = $entityManager->getRepository('AppBundle\Entity\Lots')
			->findOneBy(['idLot'=>$idLot])
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

			//return $this->redirectToRoute('visa_login');

		}



		$data =
		[
				'nomOrganisme'				=> $nomOrganisme,
				'nomUtilisateur'			=> $nomUtilisateur,
				'numeroAffaire'				=> $numeroAffaire,
				'idAffaire' 				=> $idAffaire,

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
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires/Affaire{numeroAffaire}/ID{idAffaire}/Lot{idLot}/Item{idItem}", name="visa_remarques")
	 */
	public function showVisaRemarques($nomOrganisme, $nomUtilisateur, $numeroAffaire, $idAffaire, $idLot, $idItem, Request $request) {

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
		}


		$data =
		[
				'nomOrganisme'				=> $nomOrganisme,
				'nomUtilisateur'			=> $nomUtilisateur,
				'numeroAffaire'				=> $numeroAffaire,
				'idAffaire' 				=> $idAffaire,

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
