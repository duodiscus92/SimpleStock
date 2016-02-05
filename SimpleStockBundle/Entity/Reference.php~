<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Reference
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\ReferenceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"nom"},
 * message="Ce libellé a déjà été utilisé",
 * {"ref"},
 * message="Cette référence a déjà été utilisée",
 * )
 */
class Reference
{
    // valeurs par défaut
    public function __construct()
    {
	$this->creation = new \Datetime();
	$this->modification = $this->creation;
	$this->udv = 1;
	$this->seuil = 1;
    }

    /**
     * @ORM\PreUpdate
    */
    public function updateDate()
    {
	$this->setModification(new \Datetime() );
    }

    /**
    * @ORM\ManyToOne(targetEntity="SYM16\SimpleStockBundle\Entity\Entrepot")
    * @ORM\JoinColumn(nullable=false)
    */
    private $entrepot;

    public function getEntrepot()
    {
	return $this->entrepot;
    }

    public function setEntrepot(Entrepot $entrepot)
    {
	$this->entrepot = $entrepot;
	return $this;
    }

    public function getNomEntrepot()
    {
	return $this->getEntrepot()->getNom();
    }

    /**
    * @ORM\ManyToOne(targetEntity="SYM16\SimpleStockBundle\Entity\Emplacement")
    * @ORM\JoinColumn(nullable=false)
    */
    private $emplacement;

    public function getEmplacement()
    {
	return $this->emplacement;
    }

    public function setEmplacement(Emplacement $emplacement)
    {
	$this->emplacement = $emplacement;
	return $this;
    }

    public function getNomEmplacement()
    {
	return $this->getEmplacement()->getNom();
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
     * @ORM\Column(name="Ref", type="string", length=255, unique=true)
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="Udv", type="integer")
     */
    private $udv;

    /**
     * @var integer
     *
     * @ORM\Column(name="Seuil", type="integer")
     */
    private $seuil;

    /**
     * @var string
     *
     * @ORM\Column(name="Createur", type="string", length=255)
     */
    private $createur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Creation", type="datetime")
     */
    private $creation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Modification", type="datetime")
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
     * Set ref
     *
     * @param string $ref
     * @return Reference
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string 
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Reference
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
     * Set udv
     *
     * @param integer $udv
     * @return Reference
     */
    public function setUdv($udv)
    {
        $this->udv = $udv;

        return $this;
    }

    /**
     * Get udv
     *
     * @return integer 
     */
    public function getUdv()
    {
        return $this->udv;
    }

    /**
     * Set seuil
     *
     * @param integer $seuil
     * @return Reference
     */
    public function setSeuil($seuil)
    {
        $this->seuil = $seuil;

        return $this;
    }

    /**
     * Get seuil
     *
     * @return integer 
     */
    public function getSeuil()
    {
        return $this->seuil;
    }

    /**
     * Set createur
     *
     * @param string $createur
     * @return Reference
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
     * @return Reference
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
     * @return Reference
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
