<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lots
 *
 * @ORM\Table(name="lots", indexes={@ORM\Index(name="noms_lots_lots_fk", columns={"id_nom_lot"}), @ORM\Index(name="affaires_lots_fk", columns={"id_affaire"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LotsRepository")
 */
class Lots
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_lot", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLot;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_lot", type="smallint", nullable=false)
     */
    private $numeroLot;

    /**
     * @var \AppBundle\Entity\Affaires
     *
     * @ORM\ManyToOne(
     * 	targetEntity="AppBundle\Entity\Affaires",
     * 	inversedBy="lots"
     * )
     * 
     * @ORM\JoinColumn(
     * 	name="id_affaire",
     *  referencedColumnName="id_affaire",
     *  nullable=false
     *  )
     * 
     */
    private $idAffaire;

    /**
     * @var \AppBundle\Entity\NomsLots
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\NomsLots")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nom_lot", referencedColumnName="id_nom_lot")
     * })
     */
    private $idNomLot;
	
    /**
     * Listes des items liés au lot
     *
     * @ORM\OneToMany(
     * 	targetEntity="Items",
     * 	mappedBy="idLot"
     * )
     */
    private $items;

    /**
     * Get idLot
     *
     * @return integer
     */
    public function getIdLot()
    {
        return $this->idLot;
    }

    /**
     * Set numeroLot
     *
     * @param integer $numeroLot
     *
     * @return Lots
     */
    public function setNumeroLot($numeroLot)
    {
        $this->numeroLot = $numeroLot;

        return $this;
    }

    /**
     * Get numeroLot
     *
     * @return integer
     */
    public function getNumeroLot()
    {
        return $this->numeroLot;
    }

    /**
     * Set idAffaire
     *
     * @param \AppBundle\Entity\Affaires $idAffaire
     *
     * @return Lots
     */
    public function setIdAffaire(\AppBundle\Entity\Affaires $idAffaire = null)
    {
        $this->idAffaire = $idAffaire;

        return $this;
    }

    /**
     * Get idAffaire
     *
     * @return \AppBundle\Entity\Affaires
     */
    public function getIdAffaire()
    {
        return $this->idAffaire;
    }

    /**
     * Set idNomLot
     *
     * @param \AppBundle\Entity\NomsLots $idNomLot
     *
     * @return Lots
     */
    public function setIdNomLot(\AppBundle\Entity\NomsLots $idNomLot = null)
    {
        $this->idNomLot = $idNomLot;

        return $this;
    }

    /**
     * Get idNomLot
     *
     * @return \AppBundle\Entity\NomsLots
     */
    public function getIdNomLot()
    {
        return $this->idNomLot;
    }
    
    public function getItems()
    {
    	return $this->items;
    }
    
    /*
     * Permet d'obtenir directment le nom de lot en se servant de l'id de celui-ci
     */
    public  function getNomLot()
    {
    	return $this->getIdNomLot()->getNomLot();
    }
	
}
