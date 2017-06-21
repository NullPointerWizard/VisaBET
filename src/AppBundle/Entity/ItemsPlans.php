<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemsPlans
 *
 * @ORM\Table(name="items_plans")
 * @ORM\Entity
 */
class ItemsPlans
{
    /**
     * @var string
     *
     * @ORM\Column(name="etage", type="string", length=5, nullable=false)
     */
    private $etage;

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
     * Set etage
     *
     * @param string $etage
     *
     * @return ItemsPlans
     */
    public function setEtage($etage)
    {
        $this->etage = $etage;

        return $this;
    }

    /**
     * Get etage
     *
     * @return string
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * Set idItem
     *
     * @param \AppBundle\Entity\Items $idItem
     *
     * @return ItemsPlans
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
