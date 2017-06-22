<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visas
 *
 * @ORM\Table(name="visas", indexes={@ORM\Index(name="documents_visas_fk", columns={"id_document"}), @ORM\Index(name="index_version", columns={"version"}), @ORM\Index(name="index_id", columns={"id_item"})})
 * @ORM\Entity
 */
class Visas
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="version", type="boolean")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_emission", type="date", nullable=false)
     */
    private $dateEmission;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_visa", type="string", length=9, nullable=false)
     */
    private $etatVisa;

    /**
     * @var string
     *
     * @ORM\Column(name="indice_plan", type="string", length=4, nullable=false)
     */
    private $indicePlan;

    /**
     * @var \AppBundle\Entity\Items
     * 
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Items")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_item", referencedColumnName="id_item", unique=true)
     * })
     */
    private $idItem;

    /**
     * @var \AppBundle\Entity\Documents
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Documents")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_document", referencedColumnName="id_document")
     * })
     */
    private $idDocument;
    
    /**
     * 
     * @var \AppBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Utilisateur")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="vise_par", referencedColumnName="id_utilisateur")
     * })
     */
    private $visePar;



    /**
     * Set version
     *
     * @param boolean $version
     *
     * @return Visas
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return boolean
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set dateEmission
     *
     * @param \DateTime $dateEmission
     *
     * @return Visas
     */
    public function setDateEmission($dateEmission)
    {
        $this->dateEmission = $dateEmission;

        return $this;
    }

    /**
     * Get dateEmission
     *
     * @return \DateTime
     */
    public function getDateEmission()
    {
        return $this->dateEmission;
    }

    /**
     * Set etatVisa
     *
     * @param string $etatVisa
     *
     * @return Visas
     */
    public function setEtatVisa($etatVisa)
    {
        $this->etatVisa = $etatVisa;

        return $this;
    }

    /**
     * Get etatVisa
     *
     * @return string
     */
    public function getEtatVisa()
    {
        return $this->etatVisa;
    }

    /**
     * Set indicePlan
     *
     * @param string $indicePlan
     *
     * @return Visas
     */
    public function setIndicePlan($indicePlan)
    {
        $this->indicePlan = $indicePlan;

        return $this;
    }

    /**
     * Get indicePlan
     *
     * @return string
     */
    public function getIndicePlan()
    {
        return $this->indicePlan;
    }

    /**
     * Set idItem
     *
     * @param \AppBundle\Entity\Items $idItem
     *
     * @return Visas
     */
    public function setIdItem(\AppBundle\Entity\Items $idItem = null)
    {
        $this->idItem = $idItem;

        return $this;
    }

    /**
     * Get idItem
     *
     * @return \AppBundle\Entity\Items
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * Set idDocument
     *
     * @param \AppBundle\Entity\Documents $idDocument
     *
     * @return Visas
     */
    public function setIdDocument(\AppBundle\Entity\Documents $idDocument = null)
    {
        $this->idDocument = $idDocument;

        return $this;
    }

    /**
     * Get idDocument
     *
     * @return \AppBundle\Entity\Documents
     */
    public function getIdDocument()
    {
        return $this->idDocument;
    }
}
