<?php

namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

/**
 * Classe Acceuil
 *
 * @Route("/home")
 *
 */
class AcceuilController extends Controller
{
    /**
    *
    * @Route("/", name="sym16_simple_stock_homepage")
    */ 
    public function indexAction(/*$name*/)
    {
	//$name = 'jehrlich';
	// récupération de l'utilisateur courant
	$user = $this->getUser();
	// test si l'utilisateur est anonyme
	if(null === $user)
	    $name = 'anonyme';
	else
	    $name = $user->getUsername();
	// récuprération du service session
	$session = $this->get('session');
	// initiatisation des variables de sessions
	$session->set('stockuser', $name);

        return $this->render('SYM16SimpleStockBundle:Acceuil:index.html.twig', array('name' => $name));
    }
}
