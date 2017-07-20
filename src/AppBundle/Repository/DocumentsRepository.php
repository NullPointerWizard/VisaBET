<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author NullPointerWizard
 *
 */
class DocumentsRepository extends EntityRepository {

	public function findAllDocuments($affaireDemande)
	{
		// l'alias 'documents' fait reference a la table dans la BD
		return $this->createQueryBuilder('documents')
			->andWhere('documents.idAffaire = :idAffaireDemande')
			->setParameter('idAffaireDemande', $affaireDemande->getIdAffaire() )
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
