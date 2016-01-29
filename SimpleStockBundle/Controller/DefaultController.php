<?php

namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
	// récuprération du service session
	$session = $this->get('session');
	// initiatisation des variables de sessions
	$session->set('geii_user', $name);

        return $this->render('SYM16SimpleStockBundle:Default:index.html.twig', array('name' => $name));
    }
}
