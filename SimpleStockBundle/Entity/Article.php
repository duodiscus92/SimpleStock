<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\ArticleRepository")
 */
class Article
{
    // valeurs par défaut
    public function __construct()
    {
	$this->creation = new \Datetime();
	$this->modification = $this->creation;
	$this->tva = 20.00;
    }

    /**
    * @ORM\ManyToOne(targetEntity="SYM16\SimpleStockBundle\Entity\Reference")
    * @ORM\JoinColumn(nullable=false)
    */
    private $reference;

    public function getReference()
    {
	return $this->reference;
    }

    public function setReference(Reference $reference)
    {
	$this->reference = $reference;
	return $this;
    }

    public function getNomReference()
    {
	return $this->getReference()->getRef();
    }

    public function getLibelleReference()
    {
	return $this->getReference()->getNom();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prixht", type="decimal")
     */
    private $prixht;

    /**
     * @var string
     *
     * @ORM\Column(name="tva", type="decimal")
     */
    private $tva;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="smallint")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="createur", type="string", length=255)
     */
    private $createur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime")
     */
    private $creation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modification", type="datetime")
     */
    private $modification;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prixht
     *
     * @param string $prixht
     * @return Article
     */
    public function setPrixht($prixht)
    {
        $this->prixht = $prixht;

        return $this;
    }

    /**
     * Get prixht
     *
     * @return string 
     */
    public function getPrixht()
    {
        return $this->prixht;
    }

    /**
     * Set tva
     *
     * @param string $tva
     * @return Article
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return string 
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return Article
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set createur
     *
     * @param string $createur
     * @return Article
     */
    public function setCreateur($createur)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return string 
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * Set creation
     *
     * @param \DateTime $creation
     * @return Article
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime 
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set modification
     *
     * @param \DateTime $modification
     * @return Article
     */
    public function setModification($modification)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Get modification
     *
     * @return \DateTime 
     */
    public function getModification()
    {
        return $this->modification;
    }
}
