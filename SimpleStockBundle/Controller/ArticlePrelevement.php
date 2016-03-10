<?php
namespace SYM16\SimpleStockBundle\Controller;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ArticlePrelevement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Controller")
 */
class ArticlePrelevement{

    //nombre max de prelevement autorisé
    private $maxprelevement;
    
    //constructeur
    public function __construct($max)
    {
	$this->maxprelevement = $max;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="smallint")
     */
    private $quantite;

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
     * Set quantite
     *
     * @param integer $quantite
     * @return ArticlePrelevement
     */
    public function setQuantite($quantite)
    {
	$this->quantite = $quantite;
	return $this;
    }

    /**
     * 
     *
     * @Assert\True(message="La quantité prélevée doit être positive mais inféieure ou égale à max")
     */
    public function isLessOrEqThanMax()
    {
	if($this->quantite <= $this->maxprelevement && $this->quantite >0)
	   return true;
	else
	   return false;
    }
}
