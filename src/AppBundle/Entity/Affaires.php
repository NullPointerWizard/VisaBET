<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Affaires
 *
 * @ORM\Table(name="affaires", indexes={@ORM\Index(name="id_organisme_fk_affaires", columns={"id_organisme"})})
 * @ORM\Entity
 */
class Affaires
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_affaire", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAffaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="smallint", nullable=false)
     */
    private $year = '2017';

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_affaire", type="integer", nullable=false)
     */
    private $numeroAffaire;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_affaire", type="string", length=100, nullable=false)
     */
    private $nomAffaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_butoir", type="date", nullable=true)
     */
    private $dateButoir;

    /**
     * @var string
     *
     * @ORM\Column(name="travail_a_effectuer", type="string", length=300, nullable=false)
     */
    private $travailAEffectuer;

    /**
     * @var \AppBundle\Entity\Organismes
     *
     * @ORM\ManyToOne(
     * 	targetEntity="AppBundle\Entity\Organismes",
     * 	inversedBy="affaires"
     * )
     *
     * @ORM\JoinColumn(
     * 	name="id_organisme",
     * 	referencedColumnName="id_organisme",
     * 	nullable=false
     * )
     */
    private $idOrganisme;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Utilisateur", mappedBy="listeAffaires")
     */
    private $listeUtilisateur;

    /**
     * Listes des documents li�s � l'affaire
     *
     * @ORM\OneToMany(
     * 	targetEntity="Documents",
     * 	mappedBy="idAffaire"
     * )
     */
    private $documents;

    /**
     * Listes des lots lies a l'affaire
     *
     * @ORM\OneToMany(
     * 	targetEntity="Lots",
     * 	mappedBy="affaire"
     * )
     */
	private $lots;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listeUtilisateur = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->lots = new ArrayCollection();
    }


    /**
     * Get idAffaire
     *
     * @return integer
     */
    public function getIdAffaire()
    {
        return $this->idAffaire;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Affaires
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set numeroAffaire
     *
     * @param integer $numeroAffaire
     *
     * @return Affaires
     */
    public function setNumeroAffaire($numeroAffaire)
    {
        $this->numeroAffaire = $numeroAffaire;

        return $this;
    }

    /**
     * Get numeroAffaire
     *
     * @return integer
     */
    public function getNumeroAffaire()
    {
        return $this->numeroAffaire;
    }

    /**
     * Set nomAffaire
     *
     * @param string $nomAffaire
     *
     * @return Affaires
     */
    public function setNomAffaire($nomAffaire)
    {
        $this->nomAffaire = $nomAffaire;

        return $this;
    }

    /**
     * Get nomAffaire
     *
     * @return string
     */
    public function getNomAffaire()
    {
        return $this->nomAffaire;
    }

    /**
     * Set dateButoir
     *
     * @param \DateTime $dateButoir
     *
     * @return Affaires
     */
    public function setDateButoir($dateButoir)
    {
    	//Fonction modifi�e, ajout de la cr�ation de l'objet DateTime � partir d'un string
        if ($dateButoir instanceof DateTime){
            $this->dateButoir = $dateButoir;
        }else{
            $this->dateButoir = new \DateTime($dateButoir);
        }

        return $this;
    }

    /**
     * Get dateButoir
     *
     * @return \DateTime
     */
    public function getDateButoir()
    {
        return $this->dateButoir;
    }

    /**
     * Set travailAEffectuer
     *
     * @param string $travailAEffectuer
     *
     * @return Affaires
     */
    public function setTravailAEffectuer($travailAEffectuer)
    {
        $this->travailAEffectuer = $travailAEffectuer;

        return $this;
    }

    /**
     * Get travailAEffectuer
     *
     * @return string
     */
    public function getTravailAEffectuer()
    {
        return $this->travailAEffectuer;
    }

    /**
     * Set idOrganisme
     *
     * @param \AppBundle\Entity\Organismes $idOrganisme
     *
     * @return Affaires
     */
    public function setIdOrganisme(\AppBundle\Entity\Organismes $idOrganisme = null)
    {
        $this->idOrganisme = $idOrganisme;

        return $this;
    }

    /**
     * Get idOrganisme
     *
     * @return \AppBundle\Entity\Organismes
     */
    public function getIdOrganisme()
    {
        return $this->idOrganisme;
    }

    /**
     * Add utilisateur
     *
     * @param \AppBundle\Entity\Utilisateur $utilisateur
     *
     * @return Affaires
     */
    public function addListeUtilisateur(\AppBundle\Entity\Utilisateur $utilisateur)
    {
        if ($this->listeUtilisateur->contains($utilisateur)) {
           return;
       }
        $this->listeUtilisateur->add($utilisateur);

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \AppBundle\Entity\Utilisateur $utilisateur
     */
    public function removeListeUtilisateur(\AppBundle\Entity\Utilisateur $utilisateur)
    {
        $this->listeUtilisateur->removeElement($utilisateur);
    }

    /**
     * Get listeUtilisateur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeUtilisateur()
    {
        return $this->listeUtilisateur;
    }

	public function getDocuments()
	{
		return $this->documents;
	}

	public function getLots()
	{
		return $this->lots;
	}

    public function __toString()
    {
        return $this->getNomAffaire();
    }


}
