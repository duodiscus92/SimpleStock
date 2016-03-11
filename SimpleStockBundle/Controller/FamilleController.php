<?php
// src/SYM16/SimpleStockBundle/Controller/FamilleController.php
namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

use SYM16\SimpleStockBundle\Entity\Famille;
use SYM16\SimpleStockBundle\Entity\Composant;
use SYM16\SimpleStockBundle\Form\FamilleType;
use SYM16\SimpleStockBundle\Form\FamilleModifierType;

/**
 *
 * Classe Famille
 *
 * @Route("/famille")
 */
class FamilleController extends /*Controller*/ SimpleStockController
{

    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	// récuprération du service session
	$session = $this->get('session');
	// récupération de la vriable de session contenant le nom interne de la connection à la BDD courante
	$stockconnection = $session->get('stockconnection');
	// selection de la database du stock courant (donc de l'entity manager)
	$this->setEmName($stockconnection);

	//pour l'accès au repositroy de l'entité dont on va s'occuper
	$this->setRepositoryPath('SYM16SimpleStockBundle:Famille');
	// sélection des colonnes à afficher
	$this
	    ->addColname('Famille',	'Nom')
	    ->addColname('Créateur',	'Createur') 
	    ->addColname('Création',	'Creation') 
	    ->addColname('Modification','Modification') 
	;

	//chemins (URL) pour les actions modifier, supprimer et lister
	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_famille_modifier',
            'supr'=> 'sym16_simple_stock_famille_supprimer',
	    'list'=> 'sym16_simple_stock_famille_lister',
	    'prop'=> 'sym16_simple_stock_famille_propriete')
	);

        $this->addRoute('lister',               "sym16_simple_stock_famille_lister")
        ;

	$this->setListName("Liste des familles");

	//pour l'affichage des propriétés d'une entité
	$this->setPropertyName("Détail de la Famille :");
	$this
	    ->addProperty('Nom de la Famille',		array('Nom', 		"%s"))
	    ->addProperty('Créateur de la Famille',	array('Createur', 	"%s"))
	    ->addProperty('Date de création',		array('Creation', 	NULL))
	    ->addProperty('Date de modification',	array('Modification',	NULL))
	;
    }


    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_famille_lister")
     */
    public function listerAction()
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_EXAMINATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::listerAction();
    }

    /**
     * affcicher le proprité d'un item 
     *
     * @Route("/property", name="sym16_simple_stock_famille_propriete")
     */
    public function proprieteAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_EXAMINATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository ainsi que les propriétés à afficher
	 $this->aLister();
	// appel de la fonction mère
	return parent::proprieteAction($request);
    }

    /**
     *
     * ajouter un Famille dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_famille_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Famille);
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un famille", new FamilleType);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * modifier une famille dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_famille_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'une famille", new FamilleModifierType);
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// appel de la fonction mère
	return parent::modifierAction($request);
    }

    /**
     *
     * supprimer un famille avec traitement de l'erreur si l'famille est utilisé
     *
     * @Route("/del", name="sym16_simple_stock_famille_supprimer")
     */
    public function supprimerAction(Request $request) {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }
}
