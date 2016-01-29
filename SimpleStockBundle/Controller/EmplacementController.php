<?php
//src/SYM16/SimpleStockBundle/Controller/EmplacementController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Emplacement;
use SYM16\SimpleStockBundle\Entity\Souscategorie;
use SYM16\SimpleStockBundle\Form\EmplacementType;
//use SYM16\SimpleStockBundle\Form\EmplacementModifierType;

/**
 *
 * Classe Emplacement
 *
 * @Route("/emplacement")
 */
class EmplacementController extends Controller
{

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_emplacement_lister")
     */
    public function listerAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Emplacement');
	//preparaton des parametres
	$listColnames = array	(
				'id' => 'Id',
				'Emplacement' =>'Nom', 
				'Entrepot' => 'NomEntrepot', 
				'Créateur' => 'Createur', 
				'Création' =>'Creation', 
				'Modification' => 'Modification'
				);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
        $path=array(
                'mod'=>'sym16_simple_stock_emplacement_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_emplacement_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'Emplacements
	$totalusers = $repository->getNbEmplacement();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalusers);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    /**
     *
     * ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_emplacement_ajouter")
     */
    public function ajouterAction(Request $request){
	// creation d'une instance de l'entité propriétaire et hydratation
	$Emplacement = new Emplacement();
	// creation du formulaire
	$form = $this->createForm(new EmplacementType, $Emplacement);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Emplacement
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer Souscategorie dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($Emplacement);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'un emplacement", 'form' => $form->CreateView() )
	);
    }

    /**
     *
     * supprimer un article
     *
     * @Route("/suppr", name="sym16_simple_stock_emplacement_supprimer")
     */
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $cat = $em->getRepository("SYM16SimpleStockBundle:Emplacement")->find($id);
	// suppression de l'entité
	$em->remove($cat);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_emplacement_modifier")
     */
    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Emplacement = $em->getRepository("SYM16SimpleStockBundle:Emplacement")->find($id);
	// creation du formulaire
	$form = $this->createForm(new EmplacementType, $Emplacement);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Emplacement
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Emplacement dans la BDD
		    $em->persist($Emplacement);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Modification d'un emplacement", 'form' => $form->CreateView() )
	);
    }
}
