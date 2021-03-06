<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NomsLots
 *
 * @ORM\Table(name="noms_lots")
 * @ORM\Entity
 */
class NomsLots
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_nom_lot", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idNomLot;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_lot", type="string", length=40, nullable=false)
     */
    private $nomLot;



    /**
     * Get idNomLot
     *
     * @return integer
     */
    public function getIdNomLot()
    {
        return $this->idNomLot;
    }

    /**
     * Set nomLot
     *
     * @param string $nomLot
     *
     * @return NomsLots
     */
    public function setNomLot($nomLot)
    {
        $this->nomLot = $nomLot;

        return $this;
    }

    /**
     * Get nomLot
     *
     * @return string
     */
    public function getNomLot()
    {
        return $this->nomLot;
    }
    
    public function __toString()
    {
    	return $this->getNomLot();
    }
}
