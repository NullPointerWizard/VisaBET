<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Affaires;
use AppBundle\Form\AffaireFormType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LotFormType;
use Doctrine\Common\Collections\ArrayCollection;

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
	 * @Route("/{nomOrganisme}/{nomUtilisateur}")
	 */
	public function showDefaultVueAffaires($nomOrganisme, $nomUtilisateur)
	{
		return $this->showVueAffaires($nomOrganisme, $nomUtilisateur);
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
// 		$affaire->setNomAffaire('L\'affaire du siècle');
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
			
			return $this->redirectToRoute('connexion');
		}
		
		return $this->render('applicationVisa/nouvelle_affaire.html.twig', [
				'affaireForm' => $form->createView()
		]);
	}
	
	/**
	 * Page de visualisation d'une affaire en détails
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
			
			$this->addFlash('success', 'Lot créé !');
			
			return $this->redirectToRoute('connexion');
		}
		
		//On récupère dans la BDD les lots rattachés à l'affaire (l'allotissement) et on charge les items eventuels
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
		
		//On récupère dans la BDD les documents rattachés à l'affaire
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
	 * Page de  gestion des items pour un lot (modification et création d'items)
	 * 
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires/Affaire{numeroAffaire}/ID{idAffaire}/Lot{idLot}", name="gestion_items")
	 */
	public function showGestionItems($nomOrganisme, $nomUtilisateur, $numeroAffaire, $idAffaire, $idLot,Request $request){
		
		$entityManager = $this->getDoctrine()->getManager();
		$listeVisas = new ArrayCollection();
		$affaire = $entityManager->getRepository ( 'AppBundle\Entity\Affaires' )
			->findOneBy ([ 'idAffaire' => $idAffaire ])
		;
		$lot = $entityManager->getRepository('AppBundle\Entity\Lots')
			->findOneBy(['idLot'=>$idLot])
		;
		$listeItemsPlanLot = $entityManager->getRepository('AppBundle\Entity\Items')
			->findAllItemsWhereType($lot,'Plan');	
		$listeItemsNDCLot = $entityManager->getRepository('AppBundle\Entity\Items')
			->findAllItemsWhereType($lot,'NDC');
		$listeItemsMaterielLot = $entityManager->getRepository('AppBundle\Entity\Items')
			->findAllItemsWhereType($lot,'Materiel');
		$listeItemsAutreLot = $entityManager->getRepository('AppBundle\Entity\Items')
			->findAllItemsWhereType($lot,'Autre');
		
		foreach($listeItemsPlanLot as $item){
			$listeVisas[$item->getIdItem()] = $item->getVisas() ;
		}
		
		
			
		$data =
		[
				'nomOrganisme'				=> $nomOrganisme,
				'nomUtilisateur'			=> $nomUtilisateur,
				'numeroAffaire'				=> $numeroAffaire,
				'idAffaire' 				=> $idAffaire,
				
				'lot' 						=> $lot,
				'affaire'					=> $affaire,
				
				'listeItemsPlanLot'			=> $listeItemsPlanLot,
				'listeItemsNDCLot'			=> $listeItemsNDCLot,				
				'listeItemsMaterielLot'		=> $listeItemsMaterielLot,
				'listeItemsAutreLot'		=> $listeItemsAutreLot,
				'listeVisas'				=> $listeVisas
				
		];
		return $this->render('applicationVisa/gestion_items.html.twig', $data);
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