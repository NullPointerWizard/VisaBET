<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fiche Visa
 *
 * @ORM\Table(name="fiches_visa")
 * @ORM\Entity
 */
class FicheVisa
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_fiche", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFiche;

    /**
     * @var \AppBundle\Entity\Lots
     *
     * @ORM\ManyToOne(
     * 	targetEntity="AppBundle\Entity\Lots",
     * 	inversedBy="fiches"
     * )
     *
     * @ORM\JoinColumn(
     * 	name="id_lot",
     *  referencedColumnName="id_lot",
     *  nullable=false
     * )
     */
    private $lot;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_fiche", type="smallint", nullable=false)
     */
    private $numeroFiche;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=150, nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=2083, nullable=true)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation_fiche", type="date", nullable=true)
     */
    private $date;

    /**
     * Listes des documents de la fiche
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     * 	targetEntity="Documents",
     * 	mappedBy="fiche"
     * )
     */
    private $documents;

    public function __construct()
    {
        $this->documents =  new ArrayCollection();
    }

    public function addDocuments($document)
    {
        if ($this->documents->contains($document)) {
           return $this;
        }
        $this->documents->add($document);

        return $this;
    }



    /**
     * Get the value of Lot
     *
     * @return \AppBundle\Entity\Lots
     */
    public function getLot()
    {
        return $this->lot;
    }

    /**
     * Set the value of Lot
     *
     * @param \AppBundle\Entity\Lots lot
     *
     * @return self
     */
    public function setLot(\AppBundle\Entity\Lots $lot)
    {
        $this->lot = $lot;

        return $this;
    }

    /**
     * Get the value of Numero Fiche
     *
     * @return integer
     */
    public function getNumeroFiche()
    {
        return $this->numeroFiche;
    }

    /**
     * Set the value of Numero Fiche
     *
     * @param integer numeroFiche
     *
     * @return self
     */
    public function setNumeroFiche($numeroFiche)
    {
        $this->numeroFiche = $numeroFiche;

        return $this;
    }

    /**
     * Get the value of Filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of Filename
     *
     * @param string filename
     *
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of Path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of Path
     *
     * @param string path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of Listes des documents de la fiche
     *
     */
    public function getDocuments()
    {
        return $this->documents;
    }


    /**
     * Get the value of Date
     *
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of Date
     *
     * @param mixed date
     *
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function __toString()
    {
        return 'Fiche '.$this->getNumeroFiche();
    }

}
