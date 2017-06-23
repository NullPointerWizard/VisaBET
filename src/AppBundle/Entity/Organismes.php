<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organismes
 *
 * @ORM\Table(name="organismes")
 * @ORM\Entity
 */
class Organismes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_organisme", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrganisme;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_organisme", type="string", length=50, nullable=false)
     */
    private $nomOrganisme;



    /**
     * Get idOrganisme
     *
     * @return integer
     */
    public function getIdOrganisme()
    {
        return $this->idOrganisme;
    }

    /**
     * Set nomOrganisme
     *
     * @param string $nomOrganisme
     *
     * @return Organismes
     */
    public function setNomOrganisme($nomOrganisme)
    {
        $this->nomOrganisme = $nomOrganisme;

        return $this;
    }

    /**
     * Get nomOrganisme
     *
     * @return string
     */
    public function getNomOrganisme()
    {
        return $this->nomOrganisme;
    }
    
    public function __toString()
    {
    	return $this->getNomOrganisme();
    }
}
