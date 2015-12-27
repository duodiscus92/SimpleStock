<?php
// src/SYM16/SimpleStockBundle/Controller/MonPremierController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

class MonPremierController
{
    public function iLikeAction(){
        return new Response("J'aime beaucoup ...");
    }
}
