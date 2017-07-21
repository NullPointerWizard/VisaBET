<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roles
 *
 * @ORM\Table(name="role_contact")
 * @ORM\Entity
 */
class RoleContact
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_role", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRoleContact;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_role", type="string", length=40, nullable=false)
     */
    private $nomRole;

    /**
     * Get the value of Id Role Contact
     *
     * @return integer
     */
    public function getIdRoleContact()
    {
        return $this->idRoleContact;
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
