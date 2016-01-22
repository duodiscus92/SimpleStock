<?php
// src/SYM16/SimpleStockBundle/Controller/SouscategorieController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Souscategorie;
use SYM16\SimpleStockBundle\Entity\Categorie;
use SYM16\SimpleStockBundle\Form\SouscategorieType;

/**
 *
 * Classe Sous Catégorie
 *
 * @Route("/souscat")
 */
class SouscategorieController extends Controller
{

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_souscategorie_lister")
     */
    public function listerAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Souscategorie');
	//preparaton des parametres
	$listColnames = array('Id', 'Libelle');
	// on récupère le contenu de la table
	$entities = $repository->findAll();
        $path=array(
                'mod'=>'sym16_simple_stock_souscategorie_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_souscategorie_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'Categories
	$totalSouscategories = $repository->getNbSouscategorie();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalSouscategories);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    /**
     *
     * ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_souscategorie_ajouter")
     */
    public function ajouterAction(Request $request){
	// creation d'une instance de l'entité propriétaire et hydratation
	$Souscategorie = new Souscategorie();
	// valeur par defaut (pour la demo on change celle du constructeur)
	$Souscategorie->setLibelle('Plomberie');
	// creation du formulaire
	$form = $this->createForm(new SouscategorieType, $Souscategorie);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Souscategorie
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer Souscategorie dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($Souscategorie);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'une sous-catégorie", 'form' => $form->CreateView() )
	);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_souscategorie_modifier")
     */
    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Souscategorie = $em->getRepository("SYM16SimpleStockBundle:Souscategorie")->find($id);
	// creation du formulaire
	$form = $this->createForm(new SouscategorieType, $Souscategorie);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Categorie
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Categorie dans la BDD
		    $em->persist($Souscategorie);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Modification d'une sous-categorie", 'form' => $form->CreateView() )
	);
    }

    /**
     *
     * supprimer un article avec traitement de l'erreur si l'article est utilisé
     *
     * @Route("/del", name="sym16_simple_stock_souscategorie_supprimer")
     */
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Souscategorie = $em->getRepository("SYM16SimpleStockBundle:Souscategorie")->find($id);
	//récupération du Libelle
	$Libelle = $Souscategorie->getLibelle();
	// avant toute tentive de supprimer  vérifier qu'aucun Categorie possède ce Libelle
        $Categories = $em->getRepository("SYM16SimpleStockBundle:Categorie")->findAll();
	foreach($Categories as  $Categorie)
	    if($Categorie->getLibelle() == $Libelle){
            //throw new \Exception(
                //'Impossible de détruire le statut : '.$Libelle.'. Un Categorie possède ce statut');
	        echo "<script>alert(\"Suppresion refusée : au moins un Categorie possède le libellé $Libelle\")</script>";
		return $this->listerAction();
	    }
	// suppression de l'entité
	$em->remove($Souscategorie);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }
}
