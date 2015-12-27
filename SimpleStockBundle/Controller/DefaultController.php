<?php

namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SYM16SimpleStockBundle:Default:index.html.twig', array('name' => $name));
    }
}
