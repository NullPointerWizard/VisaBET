<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use AppBundle\Entity\Organismes;

class LoadFixtures implements FixtureInterface 
{
	
	public function load(ObjectManager $manager) 
	{
		
		$organismes =
		[
				'Louvet',
				'Adam',
				'AUA',
				'Les architectes associes',
				'Boucherez',
				'Architecte Graille',
				'Synergiebatiment',
				'Beton Dusolide',
				'Chaudieres Lasuie',
				'Les freres Lumieres',
				'Plomberie Mario',
				'L\'architecte Dutalent'
		];
		
		foreach ($organismes as $nomOrganisme){
			$organisme = new Organismes();
			$organisme->setNomOrganisme($nomOrganisme);
			
			$manager->persist($organisme);
		}
		$manager->flush();
		
		
		
		
		$objects = Fixtures::load(
			__DIR__.'/fixtures.yml',
			$manager,
			[
					//Les providers permettent d'utiliser les fonctions formatters, qui sont les fonctions générant des datas
					'providers' => [$this]
			]
		);
	}
	
	
	/*
	 * FORMATTER
	 */
	
	// -------------- TABLE Affaires ----------------
	
	function formatterTravailAEffectuer()
	{
		$genera = 
		[
				'A jour',
				'',
				'Boire un coup avec le client',
				'Visas plan',
				'Visas NDC+plans',
				'Visas materiel',
				'Bonne annee',
				'Revoir les items en fonction de la derniere FTM',
		];
		
		$key = array_rand($genera);
		return $genera[$key];
	}
	
	
	
	//--------------- TABLE Documents -----------------------
	
	function formatterTypeDocument()
	{
		$genera =
		[
				'NDC',
				'Materiel',
				'Plan',
				'Autre'
		];
		
		$key = array_rand($genera);
		return $genera[$key];
	}
	
	function formatterFilename()
	{
		$genera =
		[
				'NDC',
				'Materiel',
				'Plan',
				'Autre'
		];
		
		$key = array_rand($genera);
		return $genera[$key];
	}
	
	
}