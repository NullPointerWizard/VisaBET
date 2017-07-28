<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Lots;
/**
 * Contact
 *
 * @ORM\Table(
 *  name="contact"
 * )
 * @ORM\Entity
 *
 */
class Contact
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_contact", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idContact;

    /**
     * @var \AppBundle\Entity\Organismes
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organismes")
     *
     * @ORM\JoinColumn(
     *  name="id_organisme",
     *  referencedColumnName="id_organisme",
     *  nullable=true
     * )
     */
    private $organisme;

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
     * @var \AppBundle\Entity\RoleContact
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RoleContact")
     * @ORM\JoinColumn(name="id_role", referencedColumnName="id_role")
     *
     */
    private $role;

    /**
     *
     *
     * @var string
     *
     * @ORM\Column(
     *  name="mail",
     *  type="string",
     *  length=100,
     *  nullable=true
     * )
     *
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20, nullable=true)
     */
    private $tel;



    /**
     * La liste des lots sur lesquels le contact est dans la liste de diffusion
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Lots", mappedBy="listeDiffusion")
     */
    private $listeLots;


    public function __construct()
    {
        $this->listeLots = new ArrayCollection();
    }

    /**
     * Get the value of Id Contact
     *
     * @return integer
     */
    public function getIdContact()
    {
        return $this->idContact;
    }

    /**
     * Get the value of Organisme
     *
     * @return \AppBundle\Entity\Organismes
     */
    public function getOrganisme()
    {
        return $this->organisme;
    }

    /**
     * Set the value of Organisme
     *
     * @param \AppBundle\Entity\Organismes organisme
     *
     * @return self
     */
    public function setOrganisme(\AppBundle\Entity\Organismes $organisme)
    {
        $this->organisme = $organisme;

        return $this;
    }

    /**
     * Get the value of Nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of Nom
     *
     * @param string nom
     *
     * @return self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of Prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of Prenom
     *
     * @param string prenom
     *
     * @return self
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of Role
     *
     * @return \AppBundle\Entity\RoleContact
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of Role
     *
     * @param \AppBundle\Entity\RoleContact role
     *
     * @return self
     */
    public function setRole(\AppBundle\Entity\RoleContact $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of Sert d'identifiant
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of Sert d'identifiant
     *
     * @param string mail
     *
     * @return self
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get the value of Tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set the value of Tel
     *
     * @param string tel
     *
     * @return self
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }


    /**
     * Get the value of La liste des lots sur lesquels le contact est dans la liste de diffusion
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getListeLots()
    {
        return $this->listeLots;
    }

    /**
     * Ajoute un lot a la liste du contact
     *
     * @param Lots $lot
     *
     * @return self
     */
    public function addListeLots(Lots $lot)
    {
        if ($this->listeLots->contains($lot)) {
           return $this;
        }
        $this->listeLots->add($lot);

        return $this;
    }

    /*
    * Renvoie nom et prenom du contact, nom en majuscule
    */
    public function __toString()
    {
        return $this->getPrenom().' '.strtoupper($this->getNom());
    }

    /*
    * html2pdf ne supportant pas le css wordwrap:break-word cette fonction coupe le nom a une bonne taille
    */
    public function getPdfName()
    {
        //le nom et le prenom sont coupes par wordwrap si ils ont trop de caracteres
        return wordwrap($this->getPrenom(), 20, ' ', true).' '.wordwrap(strtoupper($this->getNom()), 15, ' ', true);
    }

    /*
    * html2pdf ne supportant pas le css wordwrap:break-word cette fonction coupe le mail a une bonne taille
    */
    public function getPdfMail()
    {
        return wordwrap($this->getMail(), 60, ' ', true);
    }
}
