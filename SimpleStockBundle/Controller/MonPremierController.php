<?php
// src/SYM16/SimpleStockBundle/Controller/MonPremierController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/*class MonPremierController extends Controller
{
    public function iLikeAction(){
	$content = $this->get('templating')->render
		('SYM16SimpleStockBundle:MonPremier:iLike.html.twig');
        return new Response($content);
    }
}*/

class MonPremierController extends Controller
{
    public function iLikeAction($un_verbe_a_l_infinitif){
        return new Response("J'aime beaucoup ".$un_verbe_a_l_infinitif.' !');
    }

    public function produitAction($multiplicande, $multiplicateur){
	$produit = $multiplicande * $multiplicateur;
	return new Response
	   ("Le produit de " . $multiplicande . " par ". $multiplicateur. 
		" est égale à : ". $produit); 
    }
}
