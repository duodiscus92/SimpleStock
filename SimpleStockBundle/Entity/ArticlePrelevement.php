<?php
namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/*
 * ArticlePrelevement
 */
class ArticlePrelevement{

    //nombre max de prelevement autorisé
    private $maxprelevement;
    
    //constructeur
    public function __construct($max)
    {
	$this->maxprelevement = $max;
    }

    // quantitié à prélever
    private $quantite;

    public function getQuantite()
    {
	return $this->quantite;
    }

    public function setQuantite($quantite)
    {
	$this->quantite = $quantite;
	return $this;
    }

    /**
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
