<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemsMateriel
 *
 * @ORM\Table(name="items_materiel")
 * @ORM\Entity
 */
class ItemsMateriel
{
    /**
     * @var string
     *
     * @ORM\Column(name="ensemble", type="string", length=40, nullable=false)
     */
    private $ensemble = 'Autre';

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
     * Set ensemble
     *
     * @param string $ensemble
     *
     * @return ItemsMateriel
     */
    public function setEnsemble($ensemble)
    {
        $this->ensemble = $ensemble;

        return $this;
    }

    /**
     * Get ensemble
     *
     * @return string
     */
    public function getEnsemble()
    {
        return $this->ensemble;
    }

    /**
     * Set idItem
     *
     * @param \AppBundle\Entity\Items $idItem
     *
     * @return ItemsMateriel
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
