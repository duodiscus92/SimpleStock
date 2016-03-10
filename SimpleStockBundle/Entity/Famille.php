<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Famille
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\FamilleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"nom"},
 * message="Ce nom  de Famille a déjà été utilisé"
 * )
 */
class Famille extends EntityTools
{
    public function __construct()
    {
	//$this->creation = new \Datetime();
	//$this->modification = $this->creation;
	// pour la relation bi-directionnelle
	$this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
    */
    public function updateDate()
    {
	$this->setModification(new \Datetime() );
    }

    /**
    * pour la relation bidir
    *
    * @ORM\OneToMany(targetEntity="SYM16\SimpleStockBundle\Entity\Composant", mappedBy="famille")
    */
    private $composants;

    public function addComposant(\SYM16\SimpleStockBundle\Entity\Composant $composant)
    {
	$this->composants[] = $composant;
	$composants->setFamille($this);
	return $this;
    }

    public function removeComposant(\SYM16\SimpleStockBundle\Entity\Composant $composant)
    {
	$this->composants->removeElement($composant);
    }

    public function getComposants()
    {
	return $this->composants;
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     * @Assert\Length(
     * min=4,
     * max=50,
     * minMessage="Nom de Famille doit faire au moins {{ limit }} caractères",
     * maxMessage="Nom de Famille doit faire au plus {{ limit }} caractères"
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
     * 
     *
     * @Assert\True(message="Le créateur et le nom de Famille ne peuvent pas être identiques")
     */
    public function isNomNeCreateur()
    {
	if($this->nom == $this->createur)
	    return false;
        else
	    return true;
    }

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
     * @return Famille
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
     * @return Famille
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
     * @return Famille
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
     * @return Famille
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
