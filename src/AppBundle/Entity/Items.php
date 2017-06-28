<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Items
 *
 * @ORM\Table(name="items", indexes={@ORM\Index(name="lots_item_fk", columns={"id_lot"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemsRepository")
 */
class Items
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_item", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idItem;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_item", type="string", length=50, nullable=false)
     */
    private $nomItem;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="tag", type="string", length=25, nullable=false)
     */
    private $tag;
    

    /**
     * @var \AppBundle\Entity\Lots
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lots")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_lot", referencedColumnName="id_lot")
     * })
     */
    private $idLot;



    /**
     * Get idItem
     *
     * @return integer
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Items
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nomItem
     *
     * @param string $nomItem
     *
     * @return Items
     */
    public function setNomItem($nomItem)
    {
        $this->nomItem = $nomItem;

        return $this;
    }

    /**
     * Get nomItem
     *
     * @return string
     */
    public function getNomItem()
    {
        return $this->nomItem;
    }

    /**
     * Set idLot
     *
     * @param \AppBundle\Entity\Lots $idLot
     *
     * @return Items
     */
    public function setIdLot(\AppBundle\Entity\Lots $idLot = null)
    {
        $this->idLot = $idLot;

        return $this;
    }

    /**
     * Get idLot
     *
     * @return \AppBundle\Entity\Lots
     */
    public function getIdLot()
    {
        return $this->idLot;
    }
}
