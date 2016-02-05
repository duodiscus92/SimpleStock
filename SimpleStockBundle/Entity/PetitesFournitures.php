<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PetitesFournitures
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\PetitesFournituresRepository")
 */
class PetitesFournitures
{
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return PetitesFournitures
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
     * Set prixht
     *
     * @param string $prixht
     * @return PetitesFournitures
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
     * @return PetitesFournitures
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

    public function getTtc()
    {
	return $this->prixht * (1 + $this->tva/100.0);
    }
}
