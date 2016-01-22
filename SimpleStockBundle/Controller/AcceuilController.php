<?php

namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 *
 * Classe Acceuil Controller
 *
 * @Route("/home")
 */
class AcceuilController extends Controller
{
    /**
    *
    * @Route("/{name}", name="sym16_simple_stock_homepage")
    */ 
    public function indexAction($name)
    {
        return $this->render('SYM16SimpleStockBundle:Acceuil:index.html.twig', array('name' => $name));
    }
}
