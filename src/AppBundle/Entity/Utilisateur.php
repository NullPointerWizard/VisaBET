<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Utilisateur
 *
 * @ORM\Table(
 *  name="utilisateur",
 *  indexes={@ORM\Index(name="id_organisme_fk_utilisateur", columns={"id_organisme"})}
 * )
 * @ORM\Entity(
 *  repositoryClass="AppBundle\Repository\UtilisateurRepository"
 * )
 *
 * @UniqueEntity(fields={"mail"}, message="Cette adresse est déjà utilisée")
 */
class Utilisateur implements UserInterface
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
     * Sert d'identifiant
     *
     * @var string
     *
     * @ORM\Column(
     *  name="mail",
     *  type="string",
     *  unique=true,
     *  length=100,
     *  nullable=false
     * )
     * @Assert\NotBlank(groups={"creer_utilisateur"})
     * @Assert\Email(message="Vérifiez le format de l'adresse")
     *
     */
    private $mail;

    /**
     * The encoded password
     *
     * @ORM\Column(
     *  type="string"
     * )
     */
    private $password;

    /**
    * A non-persisted field that's used to create the encoded password.
    *
    * @var string
    * @Assert\NotBlank(groups={"creer_utilisateur"})
    */
    private $plainPassword;

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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Affaires", inversedBy="listeUtilisateur")
     * @ORM\JoinTable(name="work_on",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_affaire", referencedColumnName="id_affaire")
     *   }
     * )
     */
    private $listeAffaires;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listeAffaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add affaire
     *
     * @param \AppBundle\Entity\Affaires $affaire
     *
     * @return Utilisateur
     */
    public function addAffaire(\AppBundle\Entity\Affaires $affaire)
    {
        // On n'ajoute pas l'affaire si elle y est déjà
        if ($this->listeAffaires->contains($affaire)) {
           return;
        }
        $this->listeAffaires->add($affaire);

        return $this;
    }

    /**
     * Remove affaire
     *
     * @param \AppBundle\Entity\Affaires $affaire
     */
    public function removeAffaire(\AppBundle\Entity\Affaires $affaire)
    {
        $this->listeAffaires->removeElement($affaire);
    }

    /**
     * Get listeAffaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeAffaires()
    {
        return $this->listeAffaires;
    }

    /*
    * Renvoie nom et prenom de l'utilisateur, nom en majuscule
    */
    public function __toString()
    {
        return $this->getPrenom().' '.strtoupper($this->getNom());
    }


    //---------------- IDENTIFICATION ----------------
    // Utilise UserInterface

    /**
    * Le mail sert d'identifiant unique
    */
    public function getUsername()
    {
        //return $this->getPrenom().$this->getNom().$this->getIdUtilisateur();
        return $this->getMail();
    }

    /**
    * Les droits associes a la personne, voir config/security.yml pour la hierarchie
    */
    public function getRoles()
    {
        switch($this->getStatut()){
            case "SAdmin":
                return ['ROLE_SUPER_ADMIN'];
                break;
            case "Admin":
                return ['ROLE_ADMIN'];
                break;
            case "RespoAff":
                return ['ROLE_RESPONSABLE_AFFAIRE'] ;
                break;
            case "Visa":
                return ['ROLE_GESTION_VISAS'];
                break;
            case "Docs":
                return ['ROLE_GESTION_DOCUMENTS'];
                break;
            case "User":
                return ['ROLE_USER'];
                break;
        }
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // leaving blank - I don't need/have a password!
    }
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Get the value of A non-persisted field that's used to create the encoded password.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of A non-persisted field that's used to create the encoded password.
     *
     * @param string plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        //Cette ligne permet de faire savoir a Doctrine qu'il y a eu un changement de mdp
        //Les Doctrine listeners ne sont pas appeles si doctrine pense qu'un objet n'a pas change
        $this->password = null;
    }

}
