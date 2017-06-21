<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RemarquesVisa
 *
 * @ORM\Table(name="remarques_visa", indexes={@ORM\Index(name="types_remarque_remarques_visa_fk", columns={"id_type_remarque"}), @ORM\Index(name="index_visa", columns={"id_item", "version"}), @ORM\Index(name="index_version", columns={"version"}), @ORM\Index(name="IDX_C69B6E98943B391C", columns={"id_item"})})
 * @ORM\Entity
 */
class RemarquesVisa
{
    /**
     * @var integer
     *
     * @ORM\Column(name="no_remarque", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $noRemarque;

    /**
     * @var string
     *
     * @ORM\Column(name="remarque", type="string", length=300, nullable=false)
     */
    private $remarque;

    /**
     * @var \AppBundle\Entity\Visas
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Visas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_item", referencedColumnName="id_item", unique=true)
     * })
     */
    private $idItem;

    /**
     * @var \AppBundle\Entity\Visas
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Visas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="version", referencedColumnName="version", unique=true)
     * })
     */
    private $version;

    /**
     * @var \AppBundle\Entity\TypesRemarque
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypesRemarque")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_remarque", referencedColumnName="id_type_remarque")
     * })
     */
    private $idTypeRemarque;



    /**
     * Set noRemarque
     *
     * @param integer $noRemarque
     *
     * @return RemarquesVisa
     */
    public function setNoRemarque($noRemarque)
    {
        $this->noRemarque = $noRemarque;

        return $this;
    }

    /**
     * Get noRemarque
     *
     * @return integer
     */
    public function getNoRemarque()
    {
        return $this->noRemarque;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     *
     * @return RemarquesVisa
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * Set idItem
     *
     * @param \AppBundle\Entity\Visas $idItem
     *
     * @return RemarquesVisa
     */
    public function setIdItem(\AppBundle\Entity\Visas $idItem = null)
    {
        $this->idItem = $idItem;

        return $this;
    }

    /**
     * Get idItem
     *
     * @return \AppBundle\Entity\Visas
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * Set version
     *
     * @param \AppBundle\Entity\Visas $version
     *
     * @return RemarquesVisa
     */
    public function setVersion(\AppBundle\Entity\Visas $version = null)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return \AppBundle\Entity\Visas
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set idTypeRemarque
     *
     * @param \AppBundle\Entity\TypesRemarque $idTypeRemarque
     *
     * @return RemarquesVisa
     */
    public function setIdTypeRemarque(\AppBundle\Entity\TypesRemarque $idTypeRemarque = null)
    {
        $this->idTypeRemarque = $idTypeRemarque;

        return $this;
    }

    /**
     * Get idTypeRemarque
     *
     * @return \AppBundle\Entity\TypesRemarque
     */
    public function getIdTypeRemarque()
    {
        return $this->idTypeRemarque;
    }
}
