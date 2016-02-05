<?php
// src/SYM16/SimpleStockBundle/Controller/EntrepotController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Entrepot;
use SYM16\SimpleStockBundle\Entity\Emplacement;
use SYM16\SimpleStockBundle\Form\EntrepotType;
use SYM16\SimpleStockBundle\Form\EntrepotModifierType;

/**
 *
 * Classe Entrepot
 *
 * @Route("/entrepot")
 */
class EntrepotController extends Controller
{

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_entrepot_lister")
     */
    public function listerAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Entrepot');
	//preparaton des parametres
	$listColnames = array(	
				'id' => 'Id', 
				'Entrepot' =>	'Nom', 
				'Créateur' =>	'Createur', 
				'Création' => 'Creation', 
				'Modification' => 'Modification'
				);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
	if ($entities == NULL)
	    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
        $path=array(
                'mod'=>'sym16_simple_stock_entrepot_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_entrepot_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'entrepots
	$totalEntrepots = $repository->getNbEntrepot();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalEntrepots);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    /**
     *
     * ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_entrepot_ajouter")
     */
    public function ajouterAction(Request $request){
	// creation d'une instance de l'entité propriétaire et hydratation
	$Entrepot = new Entrepot();
	// creation du formulaire
	$form = $this->createForm(new EntrepotType, $Entrepot);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Entrepot
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer Entrepot dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($Entrepot);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'un entrepot", 'form' => $form->CreateView() )
	);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_entrepot_modifier")
     */
    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à modifier
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Entrepot = $em->getRepository("SYM16SimpleStockBundle:Entrepot")->find($id);
	// creation du formulaire
	$form = $this->createForm(new EntrepotModifierType, $Entrepot);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Emplacement
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Emplacement dans la BDD
		    $em->persist($Entrepot);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Modification d'un entrepot", 'form' => $form->CreateView() )
	);
    }

    /**
     *
     * supprimer un article avec traitement de l'erreur si l'article est utilisé
     *
     * @Route("/del", name="sym16_simple_stock_entrepot_supprimer")
     */
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Entrepot = $em->getRepository("SYM16SimpleStockBundle:Entrepot")->find($id);
	//récupération du nom de l'entrepot
	$nom = $Entrepot->getNom();
	// avant toute tentive de supprimer  vérifier qu'aucun Emplacement n'est rattaché à cet entrepot
        $Emplacements = $em->getRepository("SYM16SimpleStockBundle:Emplacement")->findAll();
	foreach($Emplacements as  $Emplacement){
	    if($Emplacement->getNomEntrepot() == $nom){
            //throw new \Exception(
                //'Impossible de détruire le statut : '.$Libelle.'. Un Emplacement possède ce statut');
	        echo "<script>alert(\"Suppresion refusée : au moins un emplacement est lié à l'entrepot $nom\")</script>";
		return $this->listerAction();
	    }
	}
	// suppression de l'entité
	echo "<script>confirm(\"Confirmez-vous la suppression de  l'entrepot $nom\")</script>";
	$em->remove($Entrepot);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }
}
