<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author NullPointerWizard
 *
 */
class UtilisateurRepository extends EntityRepository {

	public function findAllSuperAdmins()
	{
		// l'alias 'documents' fait reference a la table dans la BD
		return $this->createQueryBuilder('utilisateur')
			->andWhere('utilisateur.statut = :superAdmin')
			->setParameter('superAdmin', 'SAdmin' )
			->getQuery()
			->execute()
		;
	}

	public function getDocumentsFromLotsQueryBuilder($lot)
	{
		// l'alias 'documents' fait reference a la table dans la BD
		return $this->createQueryBuilder('documents')
			->andWhere('documents.lot = :idLotDemande')
			->setParameter('idLotDemande', $lot->getIdLot() )
		;
	}
}
