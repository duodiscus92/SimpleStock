<?php
// src/SYM16/SimpleStockBundle/Controller/DroitController.php
namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use SYM16\SimpleStockBundle\Entity\Droit;
use SYM16\SimpleStockBundle\Entity\Utilisateur;
use SYM16\SimpleStockBundle\Form\DroitType;

/**
 *
 * Classe Droit
 *
 * @Route("/droit")
 */
class DroitController extends /*Controller*/ SimpleStockController
{

    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	$this->setRepositoryPath('SYM16SimpleStockBundle:Droit');
	$this
	    ->addColname('Statut',		'Privilege')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_droit_modifier',
            'supr'=> 'sym16_simple_stock_droit_supprimer')
	);

	$this->setListName("Liste des statuts");
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_droit_lister")
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
     * @Route("/add", name="sym16_simple_stock_droit_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Droit);
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un statut", new DroitType);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_droit_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un statut", new DroitType);
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// appel de la fonction mère
	return parent::modifierAction($request);
    }

    /**
     *
     * supprimer un article avec traitement de l'erreur si l'article est utilisé
     *
     * @Route("/del", name="sym16_simple_stock_droit_supprimer")
     */
    public function supprimerAction(Request $request) {
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	$this->setMesgFlash("Statut bien supprimé");
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }
}
