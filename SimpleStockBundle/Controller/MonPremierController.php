<?php
// src/SYM16/SimpleStockBundle/Controller/MonPremierController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    //J'aime ....
    public function iLikeAction($un_verbe_a_l_infinitif){
        return new Response("J'aime beaucoup ".$un_verbe_a_l_infinitif.' !');
    }
/*
    public function produitAction($multiplicande, $multiplicateur){
	$produit = hexdec($multiplicande) * hexdec($multiplicateur);
	return new Response
	   ("Le produit de " . $multiplicande . " par ". $multiplicateur. 
		" est égale à : ". $produit); 
    }
*/
    // produit de deux nombres
    public function produitAction($multiplicande, $multiplicateur){
	$produit = hexdec($multiplicande) * hexdec($multiplicateur);
	return $this->render(
		'SYM16SimpleStockBundle:MonPremier:produit.html.twig',
		array('multiplicande' => $multiplicande,
		      'multiplicateur' => $multiplicateur,
		      'resultat' => $produit)
        );
    }

    //elevation a une puissance
    public function puissanceAction(Request $request) {
	$valeur = $request->query->get('valeur');
	$exposant = $request->query->get('exposant');
	$resultat =  pow($valeur, $exposant);
	return $this->render(
		'SYM16SimpleStockBundle:MonPremier:puissance.html.twig',
		array('valeur' => $valeur,
		      'exposant' => $exposant,
		      'resultat' => $resultat)
        );
    }

    // extraction d'une racine (pas encore implementee)
    public function racineAction($valeur, $radical){

	$url = $this->get('router')->
	    generate('sym16_simple_stock_homepage', array('name' =>'Jacques'));
	return new RedirectResponse($url);

/*	return this->redirectToRoute
	    ('sym16_simple_stock_homepage', array('name' =>'Jacques'));
*/
    }
}
