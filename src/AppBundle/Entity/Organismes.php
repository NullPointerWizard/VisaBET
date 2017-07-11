<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Organismes
 *
 * @ORM\Table(name="organismes")
 * @ORM\Entity
 */
class Organismes
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id_organisme", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOrganisme;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_organisme", type="string", length=50, nullable=false)
     */
    private $nomOrganisme;


    /**
     * Listes des affaires de l'organisme
     *
     * @ORM\OneToMany(
     * 	targetEntity="Affaires",
     * 	mappedBy="idOrganisme"
     * )
     */
    private $affaires;

    /*
     * Initialisation de la propriete(attribut php) affaires
     */
	public function __construct()
	{
		$this->affaires = new ArrayCollection();
	}

    /**
     * Get idOrganisme
     *
     * @return integer
     */
    public function getIdOrganisme()
    {
        return $this->idOrganisme;
    }

    /**
     * Set nomOrganisme
     *
     * @param string $nomOrganisme
     *
     * @return Organismes
     */
    public function setNomOrganisme($nomOrganisme)
    {
        $this->nomOrganisme = $nomOrganisme;

        return $this;
    }

    /**
     * Get nomOrganisme
     *
     * @return string
     */
    public function getNomOrganisme()
    {
        return $this->nomOrganisme;
    }

    public function getAffaires()
    {
    	return $this->affaires;
    }

	public function getFolderName()
	{
		$text = $this->getNomOrganisme();
		//SLUGIFY PART
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

	    // transliterate
	    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	    // remove unwanted characters
	    $text = preg_replace('~[^-\w]+~', '', $text);

	    // trim
	    $text = trim($text, '-');

	    // remove duplicate -
	    $text = preg_replace('~-+~', '-', $text);

	    // lowercase
	    $text = strtolower($text);

	    if (empty($text)) {
	      return 'n-a';
	    }
				
		return $text;
	}



    public function __toString()
    {
    	return $this->getNomOrganisme();
    }


}
