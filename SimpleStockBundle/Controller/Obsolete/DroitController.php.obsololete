<?php
// src/SYM16/SimpleStockBundle/Controller/DroitController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
class DroitController extends Controller
{

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_droit_lister")
     */
    public function listerAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Droit');
	//preparaton des parametres
	$listColnames = array(
				'id' => 'Id', 
				'Statut' => 'Privilege'
				);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
	if ($entities == NULL)
	    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
        $path=array(
                'mod'=>'sym16_simple_stock_droit_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_droit_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'utilisateurs
	$totaldroits = $repository->getNbDroit();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 
			'path' => $path, 'totalusers' => $totaldroits, 'listname' => "Liste des statuts");
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    /**
     *
     * ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_droit_ajouter")
     */
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

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_droit_modifier")
     */
    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $droit = $em->getRepository("SYM16SimpleStockBundle:Droit")->find($id);
	// creation du formulaire
	$form = $this->createForm(new DroitType, $droit);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $utilisateur
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer utilisateur dans la BDD
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
		array('titre' => "Modification d'un droit (avec formulaire externalisé)", 'form' => $form->CreateView() )
	);
    }

    /**
     *
     * supprimer un article avec traitement de l'erreur si l'article est utilisé
     *
     * @Route("/del", name="sym16_simple_stock_droit_supprimer")
     */
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $droit = $em->getRepository("SYM16SimpleStockBundle:Droit")->find($id);
	//récupération du privilege
	$privilege = $droit->getPrivilege();
	// avant toute tentive de supprimer  vérifier qu'aucun utilisateur possède ce privilege
        $utilisateurs = $em->getRepository("SYM16SimpleStockBundle:Utilisateur")->findAll();
	foreach($utilisateurs as  $utilisateur)
	    if($utilisateur->getPrivilege() == $privilege){
            //throw new \Exception(
                //'Impossible de détruire le statut : '.$privilege.'. Un utilisateur possède ce statut');
	        echo "<script>alert(\"Suppresion refusée : au moins un utilisateur possède le statut $privilege\")</script>";
		return $this->listerAction();
	    }
	// suppression de l'entité
	$em->remove($droit);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }

    // supprimer un article
    public function supprimerAction1(Request $request) {
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
