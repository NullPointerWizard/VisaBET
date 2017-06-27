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
	 * Renvoie les lots correspondants à l'affaire
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
}