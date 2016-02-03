<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReferenceFiltre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\ReferenceFiltreRepository")
 */
class ReferenceFiltre
{
    const FILTRE_TOUS = 't';
    const FILTRE_UNIQUEMENT = 'u';
    const FILTRE_SAUF = 's';

    public function __construct()
    {
	$this->entrepotfiltre =
	$this->categoriefiltre =
	$this->createurfiltre =
	$this->refvide = self::FILTRE_TOUS;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="refvide", type="smallint")
     */
    private $refvide;

    /**
     * @var string
     *
     * @ORM\Column(name="entrepot", type="string", length=255)
     */
    private $entrepot;

    /**
     * @var integer
     *
     * @ORM\Column(name="entrepotfiltre", type="smallint")
     */
    private $entrepotfiltre;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoriefiltre", type="smallint")
     */
    private $categoriefiltre;

    /**
     * @var string
     *
     * @ORM\Column(name="createur", type="string", length=255)
     */
    private $createur;

    /**
     * @var integer
     *
     * @ORM\Column(name="createurfiltre", type="smallint")
     */
    private $createurfiltre;


    /**
     * Set refvide
     *
     * @param integer $refvide
     * @return ReferenceFiltre
     */
    public function setRefvide($refvide)
    {
        $this->refvide = $refvide;

        return $this;
    }

    /**
     * Get refvide
     *
     * @return integer 
     */
    public function getRefvide()
    {
        return $this->refvide;
    }

    /**
     * Set entrepot
     *
     * @param string $entrepot
     * @return ReferenceFiltre
     */
    public function setEntrepot($entrepot)
    {
        $this->entrepot = $entrepot;

        return $this;
    }

    /**
     * Get entrepot
     *
     * @return string 
     */
    public function getEntrepot()
    {
        return $this->entrepot;
    }

    /**
     * Set entrepotfiltre
     *
     * @param integer $entrepotfiltre
     * @return ReferenceFiltre
     */
    public function setEntrepotfiltre($entrepotfiltre)
    {
        $this->entrepotfiltre = $entrepotfiltre;

        return $this;
    }

    /**
     * Get entrepotfiltre
     *
     * @return integer 
     */
    public function getEntrepotfiltre()
    {
        return $this->entrepotfiltre;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return ReferenceFiltre
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set categoriefiltre
     *
     * @param integer $categoriefiltre
     * @return ReferenceFiltre
     */
    public function setCategoriefiltre($categoriefiltre)
    {
        $this->categoriefiltre = $categoriefiltre;

        return $this;
    }

    /**
     * Get categoriefiltre
     *
     * @return integer 
     */
    public function getCategoriefiltre()
    {
        return $this->categoriefiltre;
    }

    /**
     * Set createur
     *
     * @param string $createur
     * @return ReferenceFiltre
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
     * Set createurfiltre
     *
     * @param integer $createurfiltre
     * @return ReferenceFiltre
     */
    public function setCreateurfiltre($createurfiltre)
    {
        $this->createurfiltre = $createurfiltre;

        return $this;
    }

    /**
     * Get createurfiltre
     *
     * @return integer 
     */
    public function getCreateurfiltre()
    {
        return $this->createurfiltre;
    }
}
