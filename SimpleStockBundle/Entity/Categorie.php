<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="Categorie", uniqueConstraints={@ORM\UniqueConstraint(name="libelle", columns={"libelle"})}, indexes={@ORM\Index(name="souscategorie_id", columns={"souscategorie_id"})})
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \SYM16\SimpleStockBundle\Entity\Souscategorie
     *
     * @ORM\ManyToOne(targetEntity="SYM16\SimpleStockBundle\Entity\Souscategorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="souscategorie_id", referencedColumnName="id")
     * })
     */
    private $souscategorie;



    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Categorie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
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
     * Set souscategorie
     *
     * @param \SYM16\SimpleStockBundle\Entity\Souscategorie $souscategorie
     * @return Categorie
     */
    public function setSouscategorie(\SYM16\SimpleStockBundle\Entity\Souscategorie $souscategorie = null)
    {
        $this->souscategorie = $souscategorie;

        return $this;
    }

    /**
     * Get souscategorie
     *
     * @return \SYM16\SimpleStockBundle\Entity\Souscategorie 
     */
    public function getSouscategorie()
    {
        return $this->souscategorie;
    }

    public function getLibelleScat()
    {
	return $this->getSouscategorie()->getLibelle();
    }
}
