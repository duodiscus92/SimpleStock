<?php
//src/SYM16/SimpleStockBundle/Controller/ArticleController.php
namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

use SYM16\SimpleStockBundle\Entity\Article;
use SYM16\SimpleStockBundle\Form\ArticleType;
//use SYM16\SimpleStockBundle\Entity\ArticleFiltre;
//use SYM16\SimpleStockBundle\Entity\Repository\ArticleFiltreRepository;
use SYM16\SimpleStockBundle\Form\ArticleModifierType;
use SYM16\SimpleStockBundle\Form\ArticleFiltreType;

/**
 *
 * Classe Article
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_article_lister")
     */
    public function listerAction()
    {

	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère tout le contenu de la table
	$repository = $em->getRepository('SYM16SimpleStockBundle:Article');
	//preparaton des parametres
	$listColnames = array	(
				'id' => 'Id',
				'Réf' => 'RefRef',
				'Nom' =>  'NomRef', 
				'PrixHT' => 'Prixht',
				'TVA' => 'Tva',
				//'Libellé' => 'Nom',
				//'UDV' => 'Udv',
				//'Seuil' => 'Seuil', 
				'Créateur' => 'Createur', 
				//'Entrepot' => 'NomEntrepot',
				//'Emplacement' => 'NomEmplacement',
				'Création' =>'Creation', 
				'Modification' => 'Modification'
				);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
	if ($entities == NULL)
	    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
        $path=array(
                'mod'=>'sym16_simple_stock_article_modifier',       // le chemin qui traitera l'action modifier
                'supr'=>'sym16_simple_stock_article_supprimer');    // le chemin qui traitera l'action supprimer
	//  nombre total d'Articles
	$repository = $em->getRepository('SYM16SimpleStockBundle:Article');
	$totalusers = $repository->getNbArticle();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 
			'path' => $path, 'totalusers' => $totalusers, 'listname' => "Liste des articles");
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    /*
     * filtrer un tableau en faisant appel à un service
     *
     * @Route("/filter", name="sym16_simple_stock_article_filtrer")
     */
    /*public function filtrerAction(Request $request)
    {
	// creation d'une instance de la classe de filtrage
	$filtre = new ArticleFiltre();
	// creation du formulaire
	$form = $this->createForm(new ArticleFiltreType, $filtre);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	    // hydrater les variables ArticleFiltre
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
		$repository = $em->getRepository('SYM16SimpleStockBundle:ArticleFiltre');
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
		if ($entities == NULL)
		    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
		// préparer les chemins modifier et effacer
		$path=array(
		    'mod'=>'sym16_simple_stock_article_modifier',       // le chemin qui traitera l'action modifier
		    'supr'=>'sym16_simple_stock_article_supprimer');    // le chemin qui traitera l'action supprimer
		//  nombre total d'Articles
		$totalusers = $repository->countByFilter(array('u' => $uniquement, 's' => $sauf));
		//on place tous les paramètres à lister dans un tableau
		$alister = array('listcolnames' => $listColnames, 'entities' => $entities, 'path' => $path, 'totalusers' => $totalusers);
		// récupération du service et de la prestation  "lister_tout"
		$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
		//lister
		//if ($service == NULL)
		    //return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
		//else
		    return $this->render($service['listtwig'], $service['tab']);
	    }
	}
	//afficher le formulaire et le passer à la vue
    	    return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Filtre d'affichage des articles", 'form' => $form->CreateView() )
	    );
    }*/

    /**
     *
     * ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_article_ajouter")
     */
    public function ajouterAction(Request $request){
	// creation d'une instance de l'entité propriétaire et hydratation
	$Article = new Article();
	// creation du formulaire
	$form = $this->createForm(new ArticleType, $Article);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Article
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer SArticle dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($Article);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	// sans l'utilsation de l'annotation Template()
	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Ajout d'un article", 'form' => $form->CreateView() )
	);
    	// avec l'utilsation de l'annotation Template()
    	//return array('titre' => "Ajout d'un article", 'form' => $form->CreateView() );
    }

    /**
     *
     * supprimer un article
     *
     * @Route("/suppr", name="sym16_simple_stock_article_supprimer")
     */
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $cat = $em->getRepository("SYM16SimpleStockBundle:Article")->find($id);
	// suppression de l'entité
	$em->remove($cat);
	$em->flush();
	// message flash
	$this->get('session')->getFlashBag()->add('info', 'Référence bien supprimée');
	//$this->get('session')->getFlashBag()->add('info', 'Presser F5 pour supprimer ce message');
	// affichage de la liste reactualisee
	return $this->listerAction();
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_article_modifier")
     */
    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $Article = $em->getRepository("SYM16SimpleStockBundle:Article")->find($id);
	// creation du formulaire
	$form = $this->createForm(new ArticleModifierType, $Article);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Article
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Article dans la BDD
		    $em->persist($Article);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
	//afficher le formulaire et le passer à la vue
    	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Modification d'un article", 'form' => $form->CreateView() )
	);
    }

    /**
     * D'après le code de Julien Cornu (merci !)
     * @Route("/article-ajax", name="article_ajax")
     */
    public function articleAjaxAction(Request $request){
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
