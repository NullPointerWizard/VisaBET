<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author NullPointerWizard
 *
 */
class ItemsRepository extends EntityRepository
{
	
	function findAllItems($lotDemande)
	{
		// l'alias 'documents fait r�f�rence � la table dans la BD
		return $this->createQueryBuilder('items')
		->andWhere('items.idLot = :idLotDemande')
		->setParameter('idLotDemande', $lotDemande->getIdLot() )
		->getQuery()
		->execute()
		;
	}
}