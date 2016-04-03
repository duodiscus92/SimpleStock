<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\ArticleRepository")
 */
class Article
{
    // valeurs par défaut
    public function __construct()
    {
	//$this->creation = new \Datetime();
	//$this->modification = $this->creation;
	$this->tva = 20.00;
	$this->reference = NULL;
	$this->commentaire = 'NEANT';
	$this->prixht = 1;
	$this->quantite = 1;
	$this->dateacquisition = new \Datetime();
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

    public function getRefRef()
    {
	return $this->getReference()->getRef();
    }

    public function getNomRef()
    {
	return $this->getReference()->getNom();
    }

    public function getNomFamille()
    {
	return $this->getReference()->getNomFamille();
    }

    public function getNomComposant()
    {
	return $this->getReference()->getNomComposant();
    }

    public function getNomEntrepot()
    {
	return $this->getReference()->getNomEntrepot();
    }

    public function getNomEmplacement()
    {
	return $this->getReference()->getNomEmplacement();
    }

    public function getUdv()
    {
	return $this->getReference()->getUdv();
    }

    public function getSeuil()
    {
	return $this->getReference()->getSeuil();
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
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255)
     */
    private $commentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateacquisition", type="datetime")
     */
    private $dateacquisition;

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

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Article
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getcommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set dateacquisition
     *
     * @param \DateTime $dateacquisition
     * @return Article
     */
    public function setDateacquisition($dateacquisition)
    {
        $this->dateacquisition = $dateacquisition;

        return $this;
    }

    /**
     * Get dateacquisition
     *
     * @return \DateTime
     */
    public function getDateacquisition()
    {
        return $this->dateacquisition;
    }

    /**
    * @Assert\True(message="La liste déroulante doit être sélectionnée" )
    *
    */
    public function isSelected()
    {
	if($this->reference == NULL) 
	   return false;
	else
	   return true;
    }
}
