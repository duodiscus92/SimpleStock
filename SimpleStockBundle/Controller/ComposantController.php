<?php
//src/SYM16/SimpleStockBundle/Controller/ComposantController.php
namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

use SYM16\SimpleStockBundle\Entity\Composant;
use SYM16\SimpleStockBundle\Form\ComposantType;
//use SYM16\SimpleStockBundle\Form\ComposantModifierType;

/**
 *
 * Classe Composant
 *
 * @Route("/composant")
 */
class ComposantController extends /*Controller*/ SimpleStockController
{
    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	$this->setRepositoryPath('SYM16SimpleStockBundle:Composant');
	$this
	    ->addColname('Composant',	'Nom')
	    ->addColName('Famille',	'NomFamille')
	    ->addColName('Créateur',	'Createur')
	    ->addColName('Création',	'Creation')
	    ->addColName('Modification','Modification')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_composant_modifier',
            'supr'=> 'sym16_simple_stock_composant_supprimer')
	);

	$this->setListName("Liste des composants");
    }


    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_composant_lister")
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
     * ajouter un composant dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_composant_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Composant);
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un composant", new ComposantType);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * supprimer un composant
     *
     * @Route("/suppr", name="sym16_simple_stock_composant_supprimer")
     */
    public function supprimerAction(Request $request) {
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	$this->setMesgFlash('Composant bien supprimé');
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }

    /**
     *
     * modifier un composant dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_composant_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un composant", new ComposantType);
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// appel de la fonction mère
	return parent::modifierAction($request);
    }
}
