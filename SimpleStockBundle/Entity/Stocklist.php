<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stocklist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\StocklistRepository")
 */
class Stocklist
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;


    /**
     * @var string
     *
     * @ORM\Column(name="usage", type="string", length=255, unique=true)
     */
    private $usage;

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
     * @return Stocklist
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
     * Set usage
     *
     * @param string $usage
     * @return Stocklist
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;

        return $this;
    }

    /**
     * Get usage
     *
     * @return string 
     */
    public function getUsage()
    {
        return $this->usage;
    }
}
