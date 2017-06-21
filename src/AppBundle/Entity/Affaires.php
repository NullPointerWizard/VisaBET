<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organismes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_organisme", referencedColumnName="id_organisme")
     * })
     */
    private $idOrganisme;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Utilisateur", mappedBy="idAffaire")
     */
    private $idUtilisateur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idUtilisateur = new \Doctrine\Common\Collections\ArrayCollection();
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
        $this->dateButoir = $dateButoir;

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
     * Add idUtilisateur
     *
     * @param \AppBundle\Entity\Utilisateur $idUtilisateur
     *
     * @return Affaires
     */
    public function addIdUtilisateur(\AppBundle\Entity\Utilisateur $idUtilisateur)
    {
        $this->idUtilisateur[] = $idUtilisateur;

        return $this;
    }

    /**
     * Remove idUtilisateur
     *
     * @param \AppBundle\Entity\Utilisateur $idUtilisateur
     */
    public function removeIdUtilisateur(\AppBundle\Entity\Utilisateur $idUtilisateur)
    {
        $this->idUtilisateur->removeElement($idUtilisateur);
    }

    /**
     * Get idUtilisateur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }
}
