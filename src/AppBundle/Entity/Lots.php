<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Affaires;
use AppBundle\Entity\NomsLots;
use AppBundle\Entity\FicheVisa;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Lots
 *
 * @ORM\Table(
 *  name="lots",
 *  indexes={
 *      @ORM\Index(name="noms_lots_lots_fk", columns={"id_nom_lot"}),
 *      @ORM\Index(name="affaires_lots_fk", columns={"id_affaire"}),
 *      @ORM\Index(name="affaires_numero_lot_index", columns={"numero_lot","id_affaire"} )
 *  },
 *  uniqueConstraints={@ORM\UniqueConstraint(name="noLotUnique", columns={"numero_lot","id_affaire"}) }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LotsRepository")
 *
 * @UniqueEntity( fields={"numeroLot", "affaire"}, message="Ce numero de lot existe deja pour cette affaire" )
 *
 */
class Lots
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_lot", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLot;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_lot", type="smallint", nullable=false)
     */
    private $numeroLot;

    /**
     * @var \AppBundle\Entity\Affaires
     *
     * @ORM\ManyToOne(
     * 	targetEntity="AppBundle\Entity\Affaires",
     * 	inversedBy="lots"
     * )
     *
     * @ORM\JoinColumn(
     * 	name="id_affaire",
     *  referencedColumnName="id_affaire",
     *  nullable=false
     *  )
     */
    private $affaire;

    /**
     * @var \AppBundle\Entity\NomsLots
     *
     * @ORM\ManyToOne(
     *  targetEntity="AppBundle\Entity\NomsLots",
     *  fetch="EAGER"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nom_lot", referencedColumnName="id_nom_lot")
     * })
     */
    private $idNomLot;

    /**
     * Listes des items lies au lot
     *
     * @ORM\OneToMany(
     * 	targetEntity="Items",
     * 	mappedBy="idLot"
     * )
     */
    private $items;


    /**
     * Listes des documents du lot
     *
     * @ORM\OneToMany(
     * 	targetEntity="Documents",
     * 	mappedBy="lot"
     * )
     */
    private $documents;

    /**
     * Listes des fiches du lot
     *
     * @ORM\OneToMany(
     * 	targetEntity="FicheVisa",
     * 	mappedBy="lot"
     * )
     */
    private $fiches;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Contact", inversedBy="listeLots")
     * @ORM\JoinTable(name="sur_liste_diffusion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_lot", referencedColumnName="id_lot")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_contact", referencedColumnName="id_contact")
     *   }
     * )
     */
     private $listeDiffusion;


    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->listeDiffusion = new ArrayCollection();
        $this->fiches = new ArrayCollection();
    }

    /**
     * Get idLot
     *
     * @return integer
     */
    public function getIdLot()
    {
        return $this->idLot;
    }

    /**
     * Set numeroLot
     *
     * @param integer $numeroLot
     *
     * @return Lots
     */
    public function setNumeroLot($numeroLot)
    {
        $this->numeroLot = $numeroLot;

        return $this;
    }

    /**
     * Get numeroLot
     *
     * @return integer
     */
    public function getNumeroLot()
    {
        return $this->numeroLot;
    }

    /**
     * Set affaire
     *
     * @param Affaires $affaire
     *
     * @return Lots
     */
    public function setAffaire(Affaires $affaire = null)
    {
        $this->affaire = $affaire;

        return $this;
    }

    /**
     * Get affaire
     *
     * @return \AppBundle\Entity\Affaires
     */
    public function getAffaire()
    {
        return $this->affaire;
    }

    /**
     * Set idNomLot
     *
     * @param NomsLots $idNomLot
     *
     * @return Lots
     */
    public function setIdNomLot(NomsLots $idNomLot = null)
    {
        $this->idNomLot = $idNomLot;

        return $this;
    }

    /**
     * Get idNomLot
     *
     * @return \AppBundle\Entity\NomsLots
     */
    public function getIdNomLot()
    {
        return $this->idNomLot;
    }

    public function getItems()
    {
    	return $this->items;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function getFiches()
    {
        return $this->fiches;
    }

    public function getListeDiffusion(){
        return $this->listeDiffusion;
    }

    /**
     * Ajoute un contact a la liste de diffusion
     *
     * @param Contact $contact
     *
     * @return self
     */
    public function addListeDiffusion(Contact $contact)
    {
        if ($this->listeDiffusion->contains($contact)) {
           return $this;
        }
        $this->listeDiffusion->add($contact);

        return $this;
    }

    /*
     * Permet d'obtenir directment le nom de lot en se servant de l'id de celui-ci
     */
    public  function getNomLot()
    {
    	return $this->getIdNomLot()->getNomLot();
    }

    public function getIdAffaire()
    {
        return $this->affaire->getIdAffaire();
    }

    public function __toString()
    {
        return 'LOT '.$this->getNumeroLot().': '.$this->getIdNomLot();
    }
}
