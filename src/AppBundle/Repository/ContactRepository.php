<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ATTENTION CETTE CLASSE N'EST PAS UTILISEE, LA BINDER DANS L'ENTITE CONTACT POUR L'UTILISER
 * @author NullPointerWizard
 *
 */
class ContactRepository extends EntityRepository {


	public function findAll()
	{
		// l'alias 'contact' fait reference a la table dans la BD
		return $this->createQueryBuilder('contact')
			->andWhere('documents.idAffaire = :idAffaireDemande')
			->setParameter('idAffaireDemande', $affaireDemande->getIdAffaire() )
		;
	}
}
