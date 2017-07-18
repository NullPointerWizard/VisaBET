<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Affaires;

/**
 *
 * @author NullPointerWizard
 *
 */
class LotsRepository extends EntityRepository {

	/*
	 * Renvoie les lots correspondants a l'affaire
	 */
	function findAllLots(Affaires $affaire)
	{
		return $this->createQueryBuilder('lots')
			->andWhere('lots.idAffaire = :idAffaireDemande')
			->setParameter('idAffaireDemande', $affaire->getIdAffaire())
			->getQuery()
			->execute()
		;
	}

	/*
	* La query renverra tous les lots de l'affaire, utilise dans un formulaire
	*/
	public function getLotsFromAffaireQueryBuilder($affaire)
	{
	  return $this->createQueryBuilder('lot')
		->where('lot.affaire = :idAffaire')
		->setParameter('idAffaire', $affaire->getIdAffaire() )
	  ;
	}

}
