<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Affaires;
use Symfony\Component\BrowserKit\Response;
use AppBundle\Form\AffaireFormType;
use Symfony\Component\HttpFoundation\Request;

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
		//Recuperation et affichage des affaires concernant l'utilisateur
		$entityManager = $this->getDoctrine()->getManager();
		$listeAffairesUtilisateur = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findAll() ;

		
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
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires/Affaire{numeroAffaire}", name="affaire_details")
	 */
	public function showAffaire($nomOrganisme, $nomUtilisateur, $numeroAffaire)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$affaire = $entityManager->getRepository('AppBundle\Entity\Affaires')
			->findOneBy(['numeroAffaire'=>$numeroAffaire])
		;
		if(!$affaire){
			throw $this->createNotFoundException('Erreur 404 not found: L\'affaire demandee est introuvable');
		}
		dump($affaire);die;
		return $this->render('applicationVisa/affaire_details.html.twig',[
				'nomOrganisme' 				=> $nomOrganisme,
				'nomUtilisateur'			=> $nomUtilisateur,
				'numeroAffaire'				=> $numeroAffaire
		]);
	}
	
	
}