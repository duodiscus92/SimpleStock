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
use SYM16\SimpleStockBundle\Entity\ReferenceFiltre;
use SYM16\SimpleStockBundle\Entity\Repository\ReferenceFiltreRepository;
use SYM16\SimpleStockBundle\Form\ReferenceModifierType;
use SYM16\SimpleStockBundle\Form\ReferenceFiltreType;

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
	$repository = $em->getRepository('SYM16SimpleStockBundle:Reference');
	$totalusers = $repository->getNbReference();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalusers);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    /**
     * filtrer un tableau en faisant appel à un service
     *
     * @Route("/filter", name="sym16_simple_stock_reference_filtrer")
     */
    public function filtrerAction(Request $request)
    {
	// creation d'une instance de la classe de filtrage
	$filtre = new ReferenceFiltre();
	// creation du formulaire
	$form = $this->createForm(new ReferenceFiltreType, $filtre);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	    // hydrater les variables ReferenceFiltre
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    $uniquement = array(); $sauf = array(); 
	    if($form->isValid()) {
		$uniquement['entrepot'] =  $sauf['entrepot'] =  $uniquement['createur'] = $sauf['createur'] = NULL;
		// construction du filtre entrepot
		if($filtre->getEntrepot() != NULL){
		    $entrepot =  $filtre->getEntrepot()->getNom();
		    if($filtre->getEntrepotfiltre() == 'u')
			$uniquement['entrepot'] = $entrepot;
		    else if($filtre->getEntrepotfiltre() == 's')
			$sauf['entrepot'] = $entrepot;
		}
		// construction du filtre createur
		if( $filtre->getCreateur() != NULL){
		    $createur =  $filtre->getCreateur()->getLogin();
		    if($filtre->getCreateurfiltre() == 'u')
		    	$uniquement['createur'] = $createur;
		    else if($filtre->getCreateurfiltre() == 's')
			$sauf['createur'] = $createur;
		}
		// on récupère l'entity manager
		$em = $this->getDoctrine()->getManager();
		// on récupère tout le contenu de la table
		$repository = $em->getRepository('SYM16SimpleStockBundle:ReferenceFiltre');
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
		// Obtenir les références filtrées
		$entities=$repository->findByFilter(array('u' => $uniquement, 's' => $sauf) );
		// préparer les chemins modifier et effacer
		$path=array(
		    'mod'=>'sym16_simple_stock_reference_modifier',       // le chemin qui traitera l'action modifier
		    'supr'=>'sym16_simple_stock_reference_supprimer');    // le chemin qui traitera l'action supprimer
		//  nombre total d'References
		$totalusers = $repository->countByFilter(array('u' => $uniquement, 's' => $sauf));
		//on place tous les paramètres à lister dans un tableau
		$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalusers);
		// récupération du service et de la prestation  "lister_tout"
		$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
		//lister
		return $this->render($service['listtwig'], $service['tab']);
	    }
	}
	//afficher le formulaire et le passer à la vue
    	    return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Filtre d'affichage des références", 'form' => $form->CreateView() )
	    );
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
    	// sans l'utilsation de l'annotation Template()
	/*return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'une reference", 'form' => $form->CreateView() )
	);*/
    	// avec l'utilsation de l'annotation Template()
    	return array('titre' => "Ajout d'une reference", 'form' => $form->CreateView() );
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
     * D'après le code de Julien Cornu (merci !)
     * @Route("/reference-ajax", name="reference_ajax")
     */
    public function referenceAjaxAction(Request $request){
	// A des fins pédago on montre qu'on peut récupérer aussi bien pat GET que par POST
	if($request->getMethod() == 'POST')
	//récupération de l'id entrepot transmise par le script Ajax par POST
            $entrepotId = $request->request->get('entrepot_id');
	//récupération de l'id entrepot transmise par le script Ajax par GET
        else
	    $entrepotId = $request->query->get('entrepot_id');
	//récupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
	// création d'un objet rsm qui va recevoir les colonnes qui nous intéressent
        $rsm = new ResultSetMapping();
	// on indique qu'on veut récupérer la colonne id
        $rsm->addScalarResult('id', 'id');
	// on indique qu'on veut récupérer la colonne Nom (libellé de la table mySql)
        $rsm->addScalarResult('Nom', 'Nom');
	// construction de la requête SQL
        $sql = "SELECT * FROM Emplacement
                WHERE 1 = 1 ";
	// si entrepotId  est défini on continue la construction pat concaténation
        if($entrepotId != '')
            $sql .= " AND Emplacement.entrepot_id = :entrepot_id ";
	// on prépare la requête
        $query = $em->createNativeQuery($sql, $rsm);
	// si entrepotId est défini on  passe entrepot_id à la requête sqp
        if($entrepotId != '')
            $query->setParameter('entrepot_id', $entrepotId);
	// on récupère le résultat : les emplacements liés à cet entrepot
        $emplacements = $query->getResult();
	// on met le résultat dans un tableau
        $emplacementsList = array();
        foreach ($emplacements as $emplacement)
            array_push($emplacementsList, array('id' => $emplacement['id'], 'nom' => $emplacement['Nom']));
	// on renvoie le réponse au client  après encodage JSON
        return   new JsonResponse($emplacementsList);
    }
}
