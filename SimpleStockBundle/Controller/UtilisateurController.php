<?php
// src/SYM16/SimpleStockBundle/Controller/UtilisateurController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Utilisateur;
use SYM16\SimpleStockBundle\Entity\Droit;
use SYM16\SimpleStockBundle\Form\UtilisateurType;
use SYM16\SimpleStockBundle\Form\UtilisateurModifierType;
use SYM16\SimpleStockBundle\Form\DroitType;

/**
 *
 * Classe Utilisateur
 *
 * @Route("/utilisateur")
 */
class UtilisateurController extends Controller
{

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_utilisateur_lister")
     */
    public function listerAction()
    {
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
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalusers);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }


    //lister un tableau (données provenenant d'une BDD)
    public function listerAction2() {
	// contruction de la première ligne (ligne d'intitulé)
	$listColnames = array('ID', 'Nom', 'Prénom', 'Droit');
	//construction des autres lignes du tableay
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Utilisateur');
	$entities = $repository->findAll();
	//$entities = $repository->getUtilisateurByStatut('GESTIONNAIRE');
	//$entities = $repository->getNbUtilisateurByStatutWithQueryBuilder('GESTIONNAIRE');
	if(null == $entities){
		throw new NotFoundHttpException("La liste n'existe pas");
	}
	$listEntities = array();
	//boucle sur les lignes de la table
	foreach ($entities as $entitie){
		// chaque ligne est elle meme un tableau d'articles qu'on obtier avec les getters
		$listArticle = array('id' => $entitie->getId(), $entitie->getNom(), $entitie->getPrenom(), $entitie->getDroit()->getPrivilege());
		// listeEntities est un tableau de tableau crée dynamiquement (on sait pas a priori le nombre de lignes)
		array_push($listEntities, $listArticle);
	}

	// construction des autres lignes
        $path=array(
		'mod'=>'sym16_simple_stock_utilisateur_modifier',	// le chemin qui traitera l'action modifier
		'supr'=>'sym16_simple_stock_utilisateur_supprimer');	// le chemin qui traitera l'action supprimer
	
	// construction	 d'information globale au entités (ex : nombre de lignes de la liste, total TTC
	$totaluser = $repository->getNbUtilisateur();
 	//$totaluser = $repository->getNbUtilisateurWithQueryBuilder();
	//$totaluser = $repository->getNbUtilisateurByIdWithQueryBuilder(3);
	return $this->render(
		'SYM16SimpleStockBundle:Common:list.html.twig',
		array('listColnames' => $listColnames, 'listEntities' => $listEntities, 'path' => $path, 'totaluser' => $totaluser)
        );
    }

    /**
     *  ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_utilisateur_ajouter")
     */
    public function ajouterAction(Request $request){
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

    // ajouter un article dans l'entité (avec formulaire)
    public function ajouterAction2()
    {

    	// créer une instance de l’objet à hydrater
    	$utilisateur = new Utilisateur;
	// hydrater certain attributs pour avoir des valeurs par défaut
	$utilisateur->setNom('Dupont');
	$utilisateur->setPrenom('Jean');
        // on rajoute en "dur" le privilège TEMPORAIRE
	// on récupère l'entité inverse correspondante au droit
	$em = $this->getDoctrine()->getManager();
	$privilege = $em->getRepository('SYM16SimpleStockBundle:Droit')->
		findOneByPrivilege('TEMPORAIRE');
	$utilisateur->setDroit($privilege);
    	// créer l’objet formulaire pour l’objet à hydrater
    	$formbuilder = $this->createFormBuilder($utilisateur);
    	// ajouter les attributs que l’on veut hydrater (pas l’id)
    	$formbuilder
	    ->add('nom', 	'text') 	// on est pas obligé d’hydrater
	    ->add('prenom', 	'text')		// tous les attributs de l’objet
	    ->add('asb', 	'checkbox', array('required' => false))
	    ->add('date', 	'datetime')
	    //->add('droit', 	new DroitType())
	    //->add('droit', 'collection', array('type' => new DroitType(),
		    //'allow_add' => true, 'allow_delete' =>true))
	    //->add('droit', 'entity', array(
	    //	'class' => 'SYM16SimpleStockBundle:Droit',
	    // 	'property' => 'nom',
	    //	'multiple' => true) )
	;
    	// générer le formulaire
    	$form = $formbuilder->getForm();
	// récupération de la requête
	$request = $this->get('request');
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
		array('titre' => "Ajout d'un utilisateur", 'form' => $form->CreateView() )
	);
    }


    // ajouter un article dans l'entité (sans formulaire, passage par GET)
    public function ajouterAction1(Request $request){
	// récuparation des valeurs passées par GET
	$nom = $request->query->get('nom');
	$prenom = $request->query->get('prenom');
	$droit = $request->query->get('droit');

	// on récupère l'entité inverse correspondante au droit (récupéré ci-dessus)
	$em = $this->getDoctrine()->getManager();
	$privilege = $em->getRepository('SYM16SimpleStockBundle:Droit')->
		findOneByPrivilege($droit);

	// creation d'une instance de l'entité propriétaire et hydratation
	$user = new Utilisateur();
	$user->setNom($nom);
	$user->setPrenom($prenom);
	$user->setDroit($privilege);

	// on persiste
	$em->persist($user);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }

    /**
     *  supprimer un article
     *
     * @Route("/del", name="sym16_simple_stock_utilisateur_supprimer")
     */
    public function supprimerAction(Request $request) {
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
     */
    public function modifierAction(Request $request)
    {
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

    // modifier un article dans l'entité (avec formulaire)
    public function modifierAction2(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $utilisateur = $em->getRepository("SYM16SimpleStockBundle:Utilisateur")->find($id);
    	// créer l’objet formulaire pour l’objet à modifier
    	$formbuilder = $this->createFormBuilder($utilisateur);
    	// ajouter les attributs que l’on veut hydrater (pas l’id)
    	$formbuilder 
	    ->add('nom', 	'text') 	// on est pas obligé d’hydrater
	    ->add('prenom', 	'text')		// tous les attributs de l’objet
	    ->add('asb', 	'checkbox', array('required' => false))
	    ->add('date', 	'datetime');
    	// générer le formulaire
    	$form = $formbuilder->getForm();
	// récupération de la requête
	$request = $this->get('request');
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
		array('titre' => "Modification d'un utilisateur", 'form' => $form->CreateView() )
	);
    }

}
