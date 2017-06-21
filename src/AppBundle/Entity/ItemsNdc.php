<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemsNdc
 *
 * @ORM\Table(name="items_ndc")
 * @ORM\Entity
 */
class ItemsNdc
{
    /**
     * @var \AppBundle\Entity\Items
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Items")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_item", referencedColumnName="id_item", unique=true)
     * })
     */
    private $idItem;



    /**
     * Set idItem
     *
     * @param \AppBundle\Entity\Items $idItem
     *
     * @return ItemsNdc
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
}
