<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Affaires;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Documents
 *
 * @ORM\Table(name="documents", indexes={@ORM\Index(name="documents_affaires_fk", columns={"id_affaire"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentsRepository")
 */
class Documents
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_document", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=true)
     */
    private $type = 'Autre';

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=150, nullable=false)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="originalFilename", type="string", length=150, nullable=true)
     */
    private $originalFilename;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reception", type="date", nullable=false)
     */
    private $dateReception;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_limite_visa", type="date", nullable=false)
     */
    private $dateLimiteVisa;

    /**
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     */
    private $etat = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=2083, nullable=false)
     */
    private $path;

    /**
     * @var \AppBundle\Entity\Affaires
     *
     * @ORM\ManyToOne(
     * 	targetEntity="AppBundle\Entity\Affaires",
     * 	inversedBy="documents"
     * )
     *
     * @ORM\JoinColumn(
     * 	name="id_affaire",
     *  referencedColumnName="id_affaire",
     *  nullable=false
     * )
     */
    private $idAffaire;


    /**
     * @var \AppBundle\Entity\Lots
     *
     * @ORM\ManyToOne(
     * 	targetEntity="AppBundle\Entity\Lots",
     * 	inversedBy="documents"
     * )
     *
     * @ORM\JoinColumn(
     * 	name="id_lot",
     *  referencedColumnName="id_lot",
     *  nullable=true
     * )
     */
    private $lot;

    /**
    * Fichier correspondant au document
    */
    private $file;

    /**
     * Get idDocument
     *
     * @return integer
     */
    public function getIdDocument()
    {
        return $this->idDocument;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Documents
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Documents
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set dateReception
     *
     * @param string $dateReception
     *
     * @return Documents
     */
    public function setDateReception($dateReception)
    {
        if ($dateReception instanceof DateTime){
            $this->dateReception = $dateReception;
        }else{
            //Cas avec un string en argument
            $this->dateReception = new \DateTime( $dateReception);
        }
        return $this;
    }

    /**
     * Get dateReception
     *
     * @return \DateTime
     */
    public function getDateReception()
    {
        return $this->dateReception;
    }

    /**
     * Set dateLimiteVisa
     *
     * @param string $dateLimiteVisa
     *
     * @return Documents
     */
    public function setDateLimiteVisa($dateLimiteVisa)
    {
        if ($dateLimiteVisa instanceof DateTime){
            $this->dateLimiteVisa = $dateLimiteVisa;
        }else{
            //Cas avec un string en argument
            $this->dateLimiteVisa = new \DateTime( $dateLimiteVisa);
        }
        return $this;
    }

    /**
     * Get dateLimiteVisa
     *
     * @return \DateTime
     */
    public function getDateLimiteVisa()
    {
        return $this->dateLimiteVisa;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Documents
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Documents
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set idAffaire
     *
     * @param \AppBundle\Entity\Affaires $idAffaire
     *
     * @return Documents
     */
    public function setIdAffaire(\AppBundle\Entity\Affaires $idAffaire = null)
    {
        $this->idAffaire = $idAffaire;

        return $this;
    }

    /**
     * Get idAffaire
     *
     * @return \AppBundle\Entity\Affaires
     */
    public function getIdAffaire()
    {
        return $this->idAffaire;
    }

    /**
     * Get the value of Original Filename
     *
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * Set the value of Original Filename
     *
     * @param string originalFilename
     *
     * @return self
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    // ------------- GESTION DES FICHIERS ---------------
    // sourcce : https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony2/creer-des-formulaires-avec-symfony2#r-2087628

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }

        // On récupère le nom original du fichier de l'internaute et l'extension qu'on rajoute au filename
        $originalFilename = $this->file->getClientOriginalName();
        $this->originalFilename = $originalFilename;
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);

        //Si le filename n'est pas ajoute on choisi l'ancien
        if(!isset($this->filename)){
            $this->filename = $this->originalFilename;
        }else{
        //S'il est ajoute on doit rajouter l'ancienne extension
            $this->filename = $this->filename.'.'.$extension;
        }


        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadRootDir(), $this->filename);

        $this->path = $this->getUploadDir().'/'.$this->filename;


    }

    /**
    * On utilise le type  du document comme nom de dossier
    */
    public function getFolderName(){
        return $this->type;
    }

    public function getUploadDir()
    {
        $affaire = $this->getIdAffaire();
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'affaires/'.$affaire->getIdOrganisme()->getFolderName().'/'.$affaire->getFolderName().'/'.'documents/'.$this->getFolderName();
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    // -------------------- AUTRE -----------------------

    public function __toString(){
        return $this->getFilename();
    }




    /**
     * Get the value of Lot
     *
     * @return \AppBundle\Entity\Lots
     */
    public function getLot()
    {
        return $this->lot;
    }

    /**
     * Set the value of Lot
     *
     * @param \AppBundle\Entity\Lots lot
     *
     * @return self
     */
    public function setLot(\AppBundle\Entity\Lots $lot)
    {
        $this->lot = $lot;

        return $this;
    }

}
