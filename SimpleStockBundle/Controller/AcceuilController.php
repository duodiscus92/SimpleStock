<?php

namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use SYM16\SimpleStockBundle\Entity\Stocklist;

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
	if(null === $user){
	    $name = 'anonyme';
	    $statut = 'VISITEUR';
	}
	else{
	    // récupérer l'identifiant et le statut
	    $name = $user->getUsername();
	    $statut = $user->getStatut();
	}

	//récupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
	$repository = $em->getRepository('SYM16SimpleStockBundle:Stocklist');

	// récuprération du service session
	$session = $this->get('session');

	// récupération du nom interne du stock courant
	$stockname = $this->container->getParameter('stockname');
	//$stockname = 'simplestock';
	echo "<script>alert($stockname)</script>";

	// récupération du nom d'usage du stock courant
	$stockusage = $repository->getCurrentUsage($stockname);

	// initiatisation des variables de sessions
	$session->set('stockuser', $name);
	$session->set('stockuserstatut', $statut);
	$session->set('stockusage', $stockusage);

        return $this->render('SYM16SimpleStockBundle:Acceuil:index.html.twig', array('name' => $name));
    }
}
