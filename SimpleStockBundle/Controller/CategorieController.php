<?php
// src/SYM16/SimpleStockBundle/Controller/CategorieController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Categorie;
use SYM16\SimpleStockBundle\Entity\Souscategorie;
use SYM16\SimpleStockBundle\Form\CategorieType;
//use SYM16\SimpleStockBundle\Form\CategorieModifierType;
use SYM16\SimpleStockBundle\Form\SouscategorieType;

class CategorieController extends Controller
{

    //lister un tableau en faisant appel à un service
    public function listerAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Categorie');
	//preparaton des parametres
	$listColnames = array('Id', 'Libelle');
	// on récupère le contenu de la table
	$entities = $repository->findAll();
        $path=array(
                'mod'=>'sym16_simple_stock_Categorie_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_Categorie_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'Categories
	$totalusers = $repository->getNbCategorie();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalusers);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    // ajouter un article dans l'entité à partir d'un formulaire externalisé
    public function ajouterAction(Request $request){
	// creation d'une instance de l'entité propriétaire et hydratation
	$Categorie = new Categorie();
	// hydrater certain attributs pour avoir des valeurs par défaut
	$Categorie->setLibelle('Outillage');
        // on rajoute en "dur" le libelle de sous categorie Plomberie
	// on récupère l'entité inverse correspondante au Souscategorie
	$em = $this->getDoctrine()->getManager();
	$libelle = $em->getRepository('SYM16SimpleStockBundle:Souscategorie')->
		findOneByLibelle('Plomberie');
	$Categorie->setSouscategorie($libelle);
	// creation du formulaire
	$form = $this->createForm(new CategorieType, $Categorie);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Categorie
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer Souscategorie dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($Categorie);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'une categorie", 'form' => $form->CreateView() )
	);
    }

    // supprimer un article
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $cat = $em->getRepository("SYM16SimpleStockBundle:Categorie")->find($id);
	// suppression de l'entité
	$em->remove($cat);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }

    // modifier un article dans l'entité (avec formulaire externalisé)
    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Categorie = $em->getRepository("SYM16SimpleStockBundle:Categorie")->find($id);
	// creation du formulaire
	$form = $this->createForm(new CategorieModifierType, $Categorie);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Categorie
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Categorie dans la BDD
		    $em->persist($Categorie);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Modification d'une ategorie", 'form' => $form->CreateView() )
	);
    }
}
