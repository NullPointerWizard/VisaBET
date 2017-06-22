<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Affaires;
use Symfony\Component\BrowserKit\Response;

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
	public function showDefaultVueAffaires($nomOrganisme,$nomUtilisateur)
	{
		return $this->showVueAffaires($nomOrganisme,$nomUtilisateur);
	}
	
	/**
	 * Page permettant la visualisation des affaires
	 * 
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires", name="affaires")
	 */
	public function showVueAffaires($nomOrganisme,$nomUtilisateur)
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
	 * Page de création des nouvelles affaires
	 * 
	 * @Route("/{nomOrganisme}/{nomUtilisateur}/Affaires/nouvelle")
	 */
	public function newAffaire()
	{
		$affaire = new Affaires();
		$affaire->setNomAffaire('L\'affaire du siècle');
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($affaire);
		$entityManager->flush();
		return new Response('<html><body>Affaire créée !</body></html>');
		//return $this->render('applicationVisa/nouvelle_affaire.html.twig');
	}
	
	
}