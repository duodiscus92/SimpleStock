<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Composant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\ComposantRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"nom"},
 * message="Ce nom  de composant a déjà été utilisé"
 * )
 */

class Composant extends EntityTools
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
    * @ORM\ManyToOne(targetEntity="SYM16\SimpleStockBundle\Entity\Famille", inversedBy="composant")
    * @ORM\JoinColumn(nullable=false)
    */
    private $famille;

    public function getFamille()
    {
	return $this->famille;
    }

    public function setFamille(Famille $famille)
    {
	$this->famille = $famille;
	return $this;
    }

    public function getNomFamille()
    {
	return $this->getFamille()->getNom();
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
     * minMessage="Nom de composant doit faire au moins {{ limit }} caractères",
     * maxMessage="Nom de composant  doit faire au plus {{ limit }} caractères"
     * )
     */
    private $nom;

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
     * Set nom
     *
     * @param string $nom
     * @return Composant
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
     * @return Composant
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
     * @return Composant
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
     * @return Composant
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
