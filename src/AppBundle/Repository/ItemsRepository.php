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
		return $this->createQueryBuilder('items')
		->andWhere('items.idLot = :idLotDemande')
		->setParameter('idLotDemande', $lotDemande->getIdLot() )
		->getQuery()
		->execute()
		;
	}

	function findAllItemsWhereType($lotDemande, $type)
	{
		return $this->createQueryBuilder('items')
		->andWhere('items.idLot = :idLotDemande')
		->andWhere('items.type = :typeDemande')
		->setParameter('idLotDemande', $lotDemande->getIdLot() )
		->setParameter('typeDemande', $type)
		->getQuery()
		->execute()
		;
	}

	public function getItemsFromLotsQueryBuilder($lot)
	{
		// l'alias 'documents' fait reference a la table dans la BD
		return $this->createQueryBuilder('items')
			->andWhere('items.idLot = :idLotDemande')
			->setParameter('idLotDemande', $lot->getIdLot() )
		;
	}
}
