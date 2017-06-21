<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypesRemarque
 *
 * @ORM\Table(name="types_remarque")
 * @ORM\Entity
 */
class TypesRemarque
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_type_remarque", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTypeRemarque;

    /**
     * @var string
     *
     * @ORM\Column(name="type_remarque", type="string", length=50, nullable=false)
     */
    private $typeRemarque;



    /**
     * Get idTypeRemarque
     *
     * @return integer
     */
    public function getIdTypeRemarque()
    {
        return $this->idTypeRemarque;
    }

    /**
     * Set typeRemarque
     *
     * @param string $typeRemarque
     *
     * @return TypesRemarque
     */
    public function setTypeRemarque($typeRemarque)
    {
        $this->typeRemarque = $typeRemarque;

        return $this;
    }

    /**
     * Get typeRemarque
     *
     * @return string
     */
    public function getTypeRemarque()
    {
        return $this->typeRemarque;
    }
}
