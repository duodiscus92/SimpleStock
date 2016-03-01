<?php
// src/SYM16/SimpleStockBundle/Controller/EntrepotController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use SYM16\SimpleStockBundle\Entity\Entrepot;
use SYM16\SimpleStockBundle\Entity\Emplacement;
use SYM16\SimpleStockBundle\Form\EntrepotType;
use SYM16\SimpleStockBundle\Form\EntrepotModifierType;

/**
 *
 * Classe Entrepot
 *
 * @Route("/entrepot")
 */
class EntrepotController extends /*Controller*/ SimpleStockController
{
    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	$this->setRepositoryPath('SYM16SimpleStockBundle:Entrepot');
	$this
	    ->addColname('Entrepot',	'Nom')
	    ->addColname('Créateur',	'Createur') 
	    ->addColname('Création',	'Creation') 
	    ->addColname('Modification','Modification') 
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_entrepot_modifier',
            'supr'=> 'sym16_simple_stock_entrepot_supprimer',
	    'list'=> 'sym16_simple_stock_entrepot_lister')
	);

	$this->setListName("Liste des entrepots");
    }


    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_entrepot_lister")
     */
    public function listerAction()
    {
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::listerAction();
    }

    /**
     *
     * ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_entrepot_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Entrepot);
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un entrepot", new EntrepotType);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_entrepot_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un entrepot", new EntrepotModifierType);
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// appel de la fonction mère
	return parent::modifierAction($request);
    }

    /**
     *
     * supprimer un article avec traitement de l'erreur si l'article est utilisé
     *
     * @Route("/del", name="sym16_simple_stock_entrepot_supprimer")
     */
    public function supprimerAction(Request $request) {
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }
}
