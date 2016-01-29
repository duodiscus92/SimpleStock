<?php
//src/SYM16/SimpleStockBundle/Controller/ReferenceController.php
namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

use SYM16\SimpleStockBundle\Entity\Reference;
use SYM16\SimpleStockBundle\Form\ReferenceType;
use SYM16\SimpleStockBundle\Form\ReferenceModifierType;

/**
 *
 * Classe Reference
 *
 * @Route("/reference")
 */
class ReferenceController extends Controller
{

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_reference_lister")
     */
    public function listerAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Reference');
	//preparaton des parametres
	$listColnames = array	(
				'id' => 'Id',
				'Réf' =>'Ref', 
				'Libellé' => 'Nom',
				'UDV' => 'Udv',
				'Seuil' => 'Seuil', 
				'Créateur' => 'Createur', 
				'Entrepot' => 'NomEntrepot',
				'Emplacement' => 'NomEmplacement',
				//'Création' =>'Creation', 
				//'Modification' => 'Modification'
				);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
        $path=array(
                'mod'=>'sym16_simple_stock_reference_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_reference_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'References
	$totalusers = $repository->getNbReference();
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
     * @Route("/add", name="sym16_simple_stock_reference_ajouter")
     * @Template()
     */
    public function ajouterAction(Request $request){
	// creation d'une instance de l'entité propriétaire et hydratation
	$Reference = new Reference();
	// creation du formulaire
	$form = $this->createForm(new ReferenceType, $Reference);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Reference
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer SReference dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($Reference);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'une reference", 'form' => $form->CreateView() )
	);
    }

    /**
     *
     * supprimer un article
     *
     * @Route("/suppr", name="sym16_simple_stock_reference_supprimer")
     */
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $cat = $em->getRepository("SYM16SimpleStockBundle:Reference")->find($id);
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
     * @Route("/mod", name="sym16_simple_stock_reference_modifier")
     */
    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Reference = $em->getRepository("SYM16SimpleStockBundle:Reference")->find($id);
	// creation du formulaire
	$form = $this->createForm(new ReferenceModifierType, $Reference);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Reference
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Reference dans la BDD
		    $em->persist($Reference);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Modification d'une reference", 'form' => $form->CreateView() )
	);
    }

    /**
     * @Route("/reference-ajax", name="reference_ajax")}
     */
    public function referenceAjaxAction(Request $request){
        $entrepotId = $request->request->get('entrepot_id');
        $em = $this->getDoctrine()->getManager();
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('nom', 'nom');
        $sql = "SELECT * FROM emplacement
                WHERE 1 = 1 ";
        if($entrepotId != '')
            $sql .= " AND emplacement.entrepot_id = :entrepot_id ";
        $query = $em->createNativeQuery($sql, $rsm);
        if($entrepotId != '')
            $query->setParameter('entrepot_id', $entrepotId);
        $emplacements = $query->getResult();
        $emplacementsList = array();
        foreach ($emplacements as $emplacement) {
            $e = array();
            $e['id'] = $emplacement['id'];
            $e['nom'] = $emplacement['nom'];
            $emplacementsList[] = $e;
        }
        return new JsonResponse($emplacementsList);
    }
}
