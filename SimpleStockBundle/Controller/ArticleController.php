<?php
//src/SYM16/SimpleStockBundle/Controller/ArticleController.php
namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

use SYM16\SimpleStockBundle\Entity\Article;
use SYM16\SimpleStockBundle\Form\ArticleType;
//use SYM16\SimpleStockBundle\Entity\ArticleFiltre;
//use SYM16\SimpleStockBundle\Entity\Repository\ArticleFiltreRepository;
use SYM16\SimpleStockBundle\Form\ArticleModifierType;
use SYM16\SimpleStockBundle\Form\ArticleFiltreType;

/**
 *
 * Classe Article
 *
 * @Route("/article")
 */
class ArticleController extends /*Controller*/ SimpleStockController
{

    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	$this->setRepositoryPath('SYM16SimpleStockBundle:Article');
	$this
	    ->addColname('Réf',		'RefRef')
	    ->addColname('Nom',		'NomRef')
	    ->addColname('PrixHT',	'Prixht')
	    ->addColname('TVA',		'Tva')
	    //->addColname('Créateur',	'Createur')
	    //->addColname('Famille',	'Famille')
	    ->addColname('Entrepot',	'NomEntrepot') 
	    ->addColname('Emplacement', 'NomEmplacement')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_article_modifier',
            'supr'=> 'sym16_simple_stock_article_supprimer')
	);

	$this->setListName("Liste des articles");
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_article_lister")
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
     * @Route("/add", name="sym16_simple_stock_article_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Article);
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un article", new ArticleType);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * supprimer un article
     *
     * @Route("/suppr", name="sym16_simple_stock_article_supprimer")
     */
    public function supprimerAction(Request $request) {
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	$this->setMesgFlash('Article bien supprimé');
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_article_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un article", new ArticleModifierType);
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// appel de la fonction mère
	return parent::modifierAction($request);
    }
}
