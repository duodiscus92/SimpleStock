<?php
// src/SYM16/SimpleStockBundle/Controller/UtilisateurController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\UserBundle\Entity\User;
use SYM16\UserBundle\Form\UserType;
//use SYM16\SimpleStockBundle\Form\UtilisateurModifierType;

/**
 *
 * Classe Utilisateur
 *
 * @Route("/utilisateur")
 */
class UtilisateurController extends /*Controller*/ SimpleStockController
{

    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	$this->setRepositoryPath('SYM16UserBundle:User');
	$this
	    //->addColname('Nom',		'Nom')
	    //->addColName('Prénom',	'Prenom')
	    //->addColName('Asb',		'Asb')
	    ->addColName('Login',	'Username')
	    ->addColName('Mdp',		'Password')
	    ->addColName('Statut',	'Statut')
	    ->addColName('Créateur',	'Createur')
	    ->addColName('Création',	'Creation')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_utilisateur_modifier',
            'supr'=> 'sym16_simple_stock_utilisateur_supprimer',
	    'list'=> 'sym16_simple_stock_utilisateur_lister',
	    'prop'=> 'sym16_simple_stock_utilisateur_propriete')
	);

	$this->setListName("Liste des utilisateurs");

	//pour l'affichage des propriétés d'une entité
	$this->setPropertyName("Détail de l'Utilisateur :");
	$this
	    //->addProperty('Nom de l\'Utilisateur',	array('Nom', 		"%s"))
	    //->addProperty('Prénom de l\'Utilisateur',	array('Prénom',		"%s"))
	    ->addProperty('Identifiant de connexion',	array('Username',	"%s"))
	    ->addProperty('Mot de passe',		array('Password', 	"%s"))
	    ->addProperty('Statut',			array('Statut',	 	"%s"))
	     ->addProperty('Créateur du l\'Utilisateur',array('Createur',       "%s"))
	    ->addProperty('Date de création',		array('Creation', 	NULL))
	    ->addProperty('Date de modification',	array('Modification',	NULL))
	;

	// change de database donc d'entity manager
	//$this->setEmName('stockmaster');
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_utilisateur_lister")
     */
    public function listerAction()
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::listerAction();
    }

    /**
     * affcicher le proprité d'un item 
     *
     * @Route("/property", name="sym16_simple_stock_utilisateur_propriete")
     */
    public function proprieteAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository ainsi que les propriétés à afficher
	 $this->aLister();
	// appel de la fonction mère
	return parent::proprieteAction($request);
    }

    /**
     *  ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_utilisateur_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new User);
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un utilisateur", new UserType);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *  supprimer un article
     *
     * @Route("/del", name="sym16_simple_stock_utilisateur_supprimer")
     */
    public function supprimerAction(Request $request) {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	//$this->setMesgFlash('Composant bien supprimé');
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }

    /**
     *   modifier un article dans l'entité (avec formulaire externalisé
     *
     * @Route("/mod", name="sym16_simple_stock_utilisateur_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un utilisateur", new EmplacementType);
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// appel de la fonction mère
	return parent::modifierAction($request);
    }
}
