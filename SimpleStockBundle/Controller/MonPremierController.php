<?php
// src/SYM16/SimpleStockBundle/Controller/MonPremierController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MonPremierController extends Controller
{
    public function iLikeAction(){
	$content = $this->get('templating')->render
		('SYM16SimpleStockBundle:MonPremier:iLike.html.twig');
        return new Response($content);
    }
}
