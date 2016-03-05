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
	    //->addColName('Privilège',	'Privilege')
	    ->addColName('Login',	'Username')
	    ->addColName('Mdp',		'Password')
	    ->addColName('Statut',	'Statut')
	    ->addColName('Créateur',	'Createur')
	    ->addColName('Création',	'Creation')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_utilisateur_modifier',
            'supr'=> 'sym16_simple_stock_utilisateur_supprimer')
	);

	$this->setListName("Liste des utilisateurs");
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

    public function listerAction1()
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Utilisateur');
	//preparaton des parametres
	$listColnames = array(
				'id' => 'Id',
				'Nom' => 'Nom',
				'Prénom' => 'Prenom', 
				'Asb' => 'Asb', 
				'Privilège' => 'Privilege', 
				'Création' => 'Date'
			 	);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
	if ($entities == NULL)
	    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
        $path=array(
                'mod'=>'sym16_simple_stock_utilisateur_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_utilisateur_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'utilisateurs
	$totalusers = $repository->getNbUtilisateur();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 
			'path' => $path, 'totalusers' => $totalusers, 'listname' => "Liste des utilisateurs");
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
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

    public function ajouterAction1(Request $request){
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de l'entité propriétaire et hydratation
	$utilisateur = new Utilisateur();
	// hydrater certain attributs pour avoir des valeurs par défaut
	$utilisateur->setNom('Dupont');
	$utilisateur->setPrenom('Jean');
        // on rajoute en "dur" le privilège TEMPORAIRE
	// on récupère l'entité inverse correspondante au droit
	$em = $this->getDoctrine()->getManager();
	$privilege = $em->getRepository('SYM16SimpleStockBundle:Droit')->
		findOneByPrivilege('TEMPORAIRE');
	$utilisateur->setDroit($privilege);
	// creation du formulaire
	$form = $this->createForm(new UtilisateurType, $utilisateur);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $utilisateur
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer droit dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($utilisateur);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'un utilisateur (formulaire externalisé)", 'form' => $form->CreateView() )
	);
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

    public function supprimerAction1(Request $request) {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $user = $em->getRepository("SYM16SimpleStockBundle:Utilisateur")->find($id);
	// suppression de l'entité
	$em->remove($user);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
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

    public function modifierAction1(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $utilisateur = $em->getRepository("SYM16SimpleStockBundle:Utilisateur")->find($id);
	// creation du formulaire
	$form = $this->createForm(new UtilisateurModifierType, $utilisateur);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $utilisateur
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer utilisateur dans la BDD
		    $em->persist($utilisateur);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Modification d'un utilisateur (avec formulaire externalisé)", 'form' => $form->CreateView() )
	);
    }
}
