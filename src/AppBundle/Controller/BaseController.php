<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 *
 * @author NullPointerWizard
 *        
 */
class BaseController extends Controller {
	
	/**
	 * @Route("/accueil/{nom}")
	 */
	public function showAction($nom)
	{
		
		$funFact = 'L\'*italique* c\'est fantastique';
		
		$cache = $this->get('doctrine_cache.providers.my_markdown_cache');
		$key = md5($funFact);
		
		if ($cache->contains($key)) {
			$funFact = $cache->fetch($key);
		}else {
				sleep(1); // fake how slow this could be
				$funFact = $this->get('markdown.parser')
				->transform($funFact);
				$cache->save($key, $funFact);
		}
			
		//$funFact = $this->get('markdown.parser')
		//	->transform($funFact);
		
		return $this->render('accueil/show.html.twig', [
				'nom' => $nom,
				'funFact' => $funFact
		]);
	}
	
	/**
	 * @Route("/accueil/{nom}/affaires", name="visa_show_affaires")
	 * @Method("GET")
	 */
	public function getAffairesAction($nom){
		$affaires = [
				['annee_affaire' => 2017, 'numero_affaire' => 1, 'nom_affaire' => 'Salle des fetes Gentilly'],
				['annee_affaire' => 2017, 'numero_affaire' => 2, 'nom_affaire' => 'Fac de sciences - Renovation des salles de TP']
		];
		
		$data = [
				'affaires' => $affaires
		];
		
		return new JsonResponse($data);
	}
}