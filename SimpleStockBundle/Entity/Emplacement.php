<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Emplacement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\EmplacementRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"nom"},
 * message="Ce nom  d'emplacement a déjà été utilisé"
 * )
 */

class Emplacement extends EntityTools
{
    public function __construct()
    {
	$this->creation = new \Datetime();
	$this->modification = $this->creation;
    }

    /**
     * @ORM\PreUpdate
    */
    public function updateDate()
    {
	$this->setModification(new \Datetime() );
    }

    /**
    * @ORM\ManyToOne(targetEntity="SYM16\SimpleStockBundle\Entity\Entrepot", inversedBy="emplacement")
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
     * @ORM\Column(name="Nom", type="string", length=255, unique=true)
     * @Assert\Length(
     * min=4,
     * max=50,
     * minMessage="Nom d'emplacement doit faire au moins {{ limit }} caractères",
     * maxMessage="Nom d'emplacement doit faire au plus {{ limit }} caractères"
     * )
     */
    private $nom;

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
     * Set nom
     *
     * @param string $nom
     * @return Emplacement
     */
    public function setNom($nom)
    {
        $this->nom = strtoupper($this->wd_remove_accents($nom));

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
     * Set createur
     *
     * @param string $createur
     * @return Emplacement
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
     * @return Emplacement
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
     * @return Emplacement
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
