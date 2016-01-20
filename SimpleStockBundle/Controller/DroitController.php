<?php
// src/SYM16/SimpleStockBundle/Controller/DroitController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Droit;
use SYM16\SimpleStockBundle\Form\DroitType;

class DroitController extends Controller
{

    //lister un tableau en faisant appel à un service
    public function listerAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Droit');
	//preparaton des parametres
	$listColnames = array('Id', 'Privilege');
	// on récupère le contenu de la table
	$entities = $repository->findAll();
        $path=array(
                'mod'=>'sym16_simple_stock_droit_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_droit_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'utilisateurs
	$totaldroits = $repository->getNbDroit();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totaldroits);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    // ajouter un article dans l'entité à partir d'un formulaire externalisé
    public function ajouterAction(Request $request){
	// creation d'une instance de l'entité propriétaire et hydratation
	$droit = new Droit();
	// valeur par defaut (pour la demo on change celle du constructeur)
	$droit->setPrivilege('VISITEUR');
	// creation du formulaire
	$form = $this->createForm(new DroitType, $droit);
	// récupération de la requête
	//$request = $this->get('request');
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $droit
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer droit dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($droit);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'un statut", 'form' => $form->CreateView() )
	);
    }

    // ajouter un article dans l'entité
    public function ajouterAction2(Request $request){
	// récuparation des valeurs passées par GET
	$privilege = $request->query->get('privilege');
	// creation d'une instance de l'entité propriétaire et hydratation
	$droit = new Droit();
	$droit->setPrivilege($privilege);
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on persiste
	$em->persist($droit);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }

    // supprimer un article
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $droit = $em->getRepository("SYM16SimpleStockBundle:Droit")->find($id);
	// suppression de l'entité
	$em->remove($droit);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }
}