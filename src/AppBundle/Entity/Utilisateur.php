<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur", indexes={@ORM\Index(name="id_organisme_fk_utilisateur", columns={"id_organisme"})})
 * @ORM\Entity
 */
class Utilisateur
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_utilisateur", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUtilisateur;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=10, nullable=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20, nullable=true)
     */
    private $tel;

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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Affaires", inversedBy="idUtilisateur")
     * @ORM\JoinTable(name="work_on",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_affaire", referencedColumnName="id_affaire")
     *   }
     * )
     */
    private $idAffaire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAffaire = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get idUtilisateur
     *
     * @return integer
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Utilisateur
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Utilisateur
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Utilisateur
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set idOrganisme
     *
     * @param \AppBundle\Entity\Organismes $idOrganisme
     *
     * @return Utilisateur
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
     * Add idAffaire
     *
     * @param \AppBundle\Entity\Affaires $idAffaire
     *
     * @return Utilisateur
     */
    public function addIdAffaire(\AppBundle\Entity\Affaires $idAffaire)
    {
        $this->idAffaire[] = $idAffaire;

        return $this;
    }

    /**
     * Remove idAffaire
     *
     * @param \AppBundle\Entity\Affaires $idAffaire
     */
    public function removeIdAffaire(\AppBundle\Entity\Affaires $idAffaire)
    {
        $this->idAffaire->removeElement($idAffaire);
    }

    /**
     * Get idAffaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdAffaire()
    {
        return $this->idAffaire;
    }

    /*
    * Renvoie nom et prenom de l'utilisateur
    */
    public function __toString()
    {
        return strtoupper($this->getNom()).' '.$this->getPrenom();
    }
}
