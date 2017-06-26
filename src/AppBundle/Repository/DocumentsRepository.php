<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author NullPointerWizard
 *        
 */
class DocumentsRepository extends EntityRepository {
	
	function findAllDocuments($affaireDemande)
	{
		// l'alias 'documents fait r�f�rence � la table dans la BD		
		return $this->createQueryBuilder('documents')
			->andWhere('documents.idAffaire = :affaireDemande')
			->setParameter('affaireDemande', $affaireDemande)
			;
	}
}