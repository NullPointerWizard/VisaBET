<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Utilisateur;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* Responsable de l'encodage/decodage des mots de passe
*/
class HashPasswordListener implements EventSubscriber
{
    /**
    * Encode les mots de passe
    *
    * @var UserPasswordEncoderInterface
    */
    private $encodeurFou;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encodeurFou = $encoder;
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    /**
    * Fonction appele avant de persister un objet avec Doctrine
    * On ne s'interrese qu'aux utilisateurs pour l'instant
    */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if(!$entity instanceof Utilisateur)
        {
            return;
        }
        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Utilisateur)
         {
            return;
        }
        $this->encodePassword($entity);

        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function encodePassword(Utilisateur $utilisateur)
    {
        if(!$utilisateur->getPlainPassword())
        {
            return;
        }

        //Encodage du mot de passe
        $encoded = $this->encodeurFou->encodePassword(
            $utilisateur,
            $utilisateur->getPlainPassword()
        );

        $utilisateur->setPassword($encoded);
    }
}
