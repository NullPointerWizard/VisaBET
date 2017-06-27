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
		//Table Organismes
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
	 * 
	 * A priori les fonctions pour les types de documents et les types d'items sont identiques.
	 * Il n'y a aucune contrainte pour l'instant entre ces 2 types (un document pourrait servir à valider plusieurs types d'items)
	 * 
	 * 
	 */
	
	// -------------- TABLE Affaires ----------------
	
	public function formatterTravailAEffectuer()
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
	
	public function formatterTypeDocument()
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
	
	public function formatterFilename()
	{
		$genera =
		[
				'NDC',
				'NDC-Dimensionnement_Chaudiere_1',
				'NDC-Dimensionnement_Ventilation_4',
				'Materiel-General',
				'Materiel-Chauffage',
				'Materiel-Electricite',
				'Materiel-Ventilation',
				'Plan',
				'Plan-R1',
				'Plan-R2',
				'Autre'
		];
		
		$key = array_rand($genera);
		return $genera[$key];
	}
	
	//---------------- TABLE Items ----------------------------
	public function formatterTypeItem()
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
	
	public function formatterNomItem(String $itemType)
	{
		
		$genera = ['NomItem'];
		
		switch($itemType)
		{
			case 'Materiel':
				$genera = 
				[
				'Thermostat',
				'Chaudiere machintruc',
				'WC autonettoyants du futur',
				'Poubelle',
				'Tubes PER',
				'Radiateur fenetre'
				]
				;
				break;
			case 'NDC':
				$genera =
				[
				'NDC Dimensionnement Chaudiere',
				'NDC Installation Electrique',
				]
				;
				break;
			case 'Plan':
				$genera = 
				[
				'Plan Salle de Bain',
				'Plan Sanitaires PMR',
				'Plan'
				]
				;
				break;
			case 'Autre':
				$genera = 
				[
				'Item rajoute a l\'arrache'
				]
				;
				break;
					
		}
		
		$key = array_rand($genera);
		return $genera[$key];
	}
	
	public function formatterEnsemble()
	{
		$genera =
		[
				'Chaudière',
				'Ventilation',
				'Sanitaires',
				'Tubes'
		];
		
		$key = array_rand($genera);
		return $genera[$key];
	}
	
	
}