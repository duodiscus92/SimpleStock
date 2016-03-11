<?php
// src/SYM16/SimpleStockBundle/Controller/StocklistController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Stocklist;
use SYM16\SimpleStockBundle\Form\StocklistType;
//use SYM16\SimpleStockBundle\Form\StocklistModifierType;

/**
 *
 * Classe Stocklist
 *
 * @Route("/stocklist")
 */
class StocklistController extends /*Controller*/ SimpleStockController
{

    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	// change de database donc d'entity manager
	$this->setEmName('stockmaster');

	$this->setRepositoryPath('SYM16SimpleStockBundle:Stocklist');
	$this
	    ->addColname('Nom',		'Nom')
	    ->addColname('Usage',	'Usage')
	    ->addColname('Connection',	'Connection')
	;

	$this->setModSupr(array(
            'mod' => 'NULL',
            'supr'=> 'NULL',
	    'list'=> 'sym16_simple_stock_stocklist_lister',
	    'prop'=> 'sym16_simple_stock_stocklist_propriete')
	);

        $this->addRoute('lister',               "sym16_simple_stock_stocklist_lister")
        ;

	$this->setListName("Liste des Stocks");

	//pour l'affichage des propriétés d'une entité
	$this->setPropertyName("Détail d'un stock :");
	$this
	    ->addProperty('Nom interne',		array('Nom', 		"%s"))
	    ->addProperty('Nom d\'usage',		array('Usage', 		"%s"))
	    ->addProperty('Nom de la connection à la base de données',		array('Connection', 		"%s"))
	;
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_stocklist_lister")
     */
    public function listerAction()
    {
	// contrôle d'accès
	//if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    //return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		//array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::listerAction();
    }

    /**
     * affcicher le proprité d'un item 
     *
     * @Route("/property", name="sym16_simple_stock_stocklist_propriete")
     */
    public function proprieteAction(Request $request)
    {
	// contrôle d'accès
	//if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    //return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		//array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository ainsi que les propriétés à afficher
	 $this->aLister();
	// appel de la fonction mère
	return parent::proprieteAction($request);
    }
}
