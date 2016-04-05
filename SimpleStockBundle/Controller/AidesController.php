<?php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *
 * Classe Aides
 *
 * @Route("/aides")
 */
class AidesController extends Controller
{

    /**
     * afficher la manuel dans sa totalité
     *
     * @Route("/usersmanual", name="sym16_simple_stock_usersmanual")
     * @Template()
     */
     // @Template("SYM16SimpleStockBubdke:Aides:usersmanuel.html.twig)
    public function usersmanualAction()
    {
	return array();
    }
}

