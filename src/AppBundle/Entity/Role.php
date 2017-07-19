<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role des contacts dans l'affaire
 *
 * @ORM\Table(name="role_contact")
 * @ORM\Entity
 */
class Role
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_role", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRole;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_role", type="string", length=40, nullable=false)
     */
    private $nomRole;

    /**
     * Get the value of Id Role
     *
     * @return integer
     */
    public function getIdRole()
    {
        return $this->idRole;
    }

    /**
     * Get the value of Nom Role
     *
     * @return string
     */
    public function getNomRole()
    {
        return $this->nomRole;
    }

    /**
     * Set the value of Nom Role
     *
     * @param string nomRole
     *
     * @return self
     */
    public function setNomRole($nomRole)
    {
        $this->nomRole = $nomRole;

        return $this;
    }

    public function __toString()
    {
        return $this->getNomRole();
    }

}
