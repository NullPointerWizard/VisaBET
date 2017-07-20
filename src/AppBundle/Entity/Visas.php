<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\SmallIntType;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineTest\InstantiatorTestAsset\FinalExceptionAsset;

/**
 * Visas
 *
 * @ORM\Table(name="visas")
 * @ORM\Entity
 */
class Visas
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id_visa", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $idVisa;

	/**
	 * @var \AppBundle\Entity\Items
	 *
	 * @ORM\ManyToOne(
	 * 	targetEntity="AppBundle\Entity\Items",
	 * 	inversedBy="visas"
	 * )
	 *
     * @ORM\JoinColumn(
     * 	name="id_item",
     *  referencedColumnName="id_item",
     *  nullable=false
     * )
	 */
	private $idItem;

    /**
     * @var SmallIntType
     *
     * @ORM\Column(name="version", type="smallint")
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
     * @var \AppBundle\Entity\Documents
     *
     * @ORM\ManyToOne(
	 *  targetEntity="AppBundle\Entity\Documents",
	 *  inversedBy="visas"
	 * )
     * @ORM\JoinColumn(
	 *	name="id_document",
	 *  referencedColumnName="id_document",
	 *  nullable=true
	 * )
     */
    private $idDocument;

    /**
     *
     * @var \AppBundle\Entity\Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Utilisateur")
	 *
     * @ORM\JoinColumn(
	 *  name="vise_par",
	 *  referencedColumnName="id_utilisateur",
	 *  nullable=false
	 * )
     */
    private $visePar;

	/**
	 * Listes des remarques du visa
	 *
	 * @var \AppBundle\Entity\RemarquesVisa
	 *
	 * @ORM\OneToMany(
	 * 	targetEntity="RemarquesVisa",
	 * 	mappedBy="idVisa"
	 * )
	 */
	private $remarques;


	public function __construct(){
		$this->remarques = new ArrayCollection();
	}

    /**
     * Set version, on utilisera toujours la derniere version pour un nouveau visa
     *
     * param SmallIntType $version
     *
     * @return Visas
     */
    public function setVersion()
    {
        $this->version = $this->getIdItem()->getVisasLastVersion()+1;

        return $this;
    }

    /**
     * Get version
     *
     * @return SmallIntType
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set dateEmission
     *
     * @param string $dateEmission
     *
     * @return Visas
     */
    public function setDateEmission($dateEmission)
    {
    	$this->dateEmission = new \DateTime($dateEmission) ;

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

	/**
	* Renvoie le nom du visa sous la forme "Vx: nomItem" avec x le numero de la version
	*/
	public function __toString()
	{
		return 'V'.$this->getVersion().': '.$this->getIdItem();
	}

    /**
     * Get the value of Vise Par
     *
     * @return \AppBundle\Entity\Utilisateur
     */
    public function getVisePar()
    {
        return $this->visePar;
    }

    /**
     * Set the value of Vise Par
     *
     * @param \AppBundle\Entity\Utilisateur $visePar
     *
     * @return self
     */
    public function setVisePar(\AppBundle\Entity\Utilisateur $visePar)
    {
        $this->visePar = $visePar;

        return $this;
    }


    /**
     * Get the value of Listes des remarques du visa
     *
     * @return \AppBundle\Entity\RemarquesVisa
     */
    public function getRemarques()
    {
        return $this->remarques;
    }

	/**
	*	Adder pour le code js permettant d'ajouter des remarques
	*
	* @param \AppBundle\Entity\RemarquesVisa $rem
	*/
	public function addRemarque(RemarquesVisa $rem)
	{
		$this->remarques->add($rem);
	}

	/**
	* Remover
	*
	* @param \AppBundle\Entity\RemarquesVisa $rem
	*/
	public function removeRemarque(RemarquesVisa $rem)
	{
		$this->remarques->removeElement($rem);
	}

	/**
	* Permet d'obtenir la classe css associée au visa
	*/
	public function getCssClass(){
		switch($this->getEtatVisa()){
			case "OK":
				return "ok";
				break;
			case "OK REM":
				return "ok-rem";
				break;
			case "OK COND":
				return "ok-rem";
				break;
			case "OK TEC":
				return "ok-tec";
				break;
			case "NC":
				return "non-conforme";
				break;
			case "REM":
				return "rem";
				break;
		}
		return "";
	}


    /**
     * Get the value of Id Visa
     *
     * @return integer
     */
    public function getIdVisa()
    {
        return $this->idVisa;
    }

}
