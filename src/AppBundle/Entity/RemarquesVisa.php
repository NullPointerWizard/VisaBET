<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RemarquesVisa
 *
 * @ORM\Table(name="remarques_visa")
 * @ORM\Entity
 */
class RemarquesVisa
{
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id_remarque_visa", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $idRemarqueVisa;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="no_remarque", type="smallint")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Visas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_visa", referencedColumnName="id_visa")
     * })
     */
    private $idVisa;

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
     *
     * @return RemarquesVisa
     */
    // @param \AppBundle\Entity\Visas $idItem (a rajouter si besoin)
    public function setIdItem($idItem = null)
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
