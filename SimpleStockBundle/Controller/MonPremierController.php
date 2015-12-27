<?php
// src/SYM16/SimpleStockBundle/Controller/MonPremierController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

class MonPremierController extends Controller
{
    public function iLikeAction(){
        return new Response("J'aime beaucoup ...");
    }
}
