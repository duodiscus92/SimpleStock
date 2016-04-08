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

//use SYM16\SimpleStockBundle\Controller\SimpleStockController;

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
class ReferenceController extends /*Controller*/ SimpleStockController
{
    private $stockconnection;
    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	// récuprération du service session
	$session = $this->get('session');
	// récupération de la vriable de session contenant le nom interne de la connection à la BDD courante
	$this->stockconnection = $session->get('stockconnection');
	// selection de la database du stock courant (donc de l'entity manager)
	$this->setEmName($this->stockconnection);

	$this->setRepositoryPath('SYM16SimpleStockBundle:Reference');
	$this
	    ->addColname('Réf',		'Ref')
	    ->addColname('Nom',		'Nom')
	    ->addColname('UDV',		'Udv')
	    ->addColname('Seuil',	'Seuil') 
	    //->addColname('Créateur',	'Createur') 
	    ->addColname('Famille',	'NomFamille')
	    ->addColname('Entrepot',	'NomEntrepot')
	    //->addColname('Emplacement',	'NomEmplacement')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_reference_modifier',
            'supr'=> 'sym16_simple_stock_reference_supprimer',
            'list'=> 'sym16_simple_stock_reference_lister',
	    'prop'=> 'sym16_simple_stock_reference_propriete')
	);

        $this->addRoute('lister',               "sym16_simple_stock_reference_lister")
        ;

	$this->setListName("Liste des références");

	//pour l'affichage des propriétés d'une reference
	$this->setPropertyName("Détails de la Référence :");
	$this
	    ->addProperty('Référence',			array('Ref',		"%s"))
	    ->addProperty('Libellé',			array('Nom',	 	"%s"))
	    ->addProperty('Unité de vente (UDV)',	array('UDV', 		"%5d"))
	    ->addProperty('Seuil d\'alerte stock bas',	array('Seuil',		"%5d"))
	    ->addProperty('Famille',			array('NomFamille',	"%s"))
	    ->addProperty('Composant',			array('NomComposant',	"%s"))
	    ->addProperty("Stocké dans l'entrepot",	array('NomEntrepot',	"%s"))
	    ->addProperty("Rangé à l'emplacement",	array('NomEmplacement',	"%s"))
	    ->addProperty("Créateur de la référence",	array('Createur', 	"%s"))
	    ->addProperty('Date et heure de création',		array('Creation', 	NULL))
	    ->addProperty('Date et heure de modification',	array('Modification',	NULL))
	;
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_reference_lister")
     */
    public function listerAction()
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_EXAMINATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::listerAction();
    }
    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/viewpdf", name="sym16_simple_stock_reference_pdflister")
     */
    public function pdflisterAction()
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_EXAMINATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::pdflisterAction();
    }

    /**
     * afficher les propriétés d'un item
     *
     * @Route("/property", name="sym16_simple_stock_reference_propriete")
     */
    public function proprieteAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_EXAMINATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository ainsi que les propriétés à afficher
	 $this->aLister();
	// appel de la fonction mère
	return parent::proprieteAction($request);
    }

    /**
     * filtrer un tableau en faisant appel à un service
     *
     * @Route("/filter", name="sym16_simple_stock_reference_filtrer")
     */
    public function filtrerAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_EXAMINATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de la classe de filtrage
	$filtre = new ReferenceFiltre();
	// creation du formulaire de saisie des parapètres du filtre
	$form = $this->createForm(new ReferenceFiltreType, $filtre);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	    // hydrater les variables ReferenceFiltre
	    $form->bind($request);
	    // verifier la validité des valeurs du formulaire
	    if($form->isValid()) {
		//ajoute des critères de filtrage (vanant du formulaire)
		// réglage filtre Entrepot
		if ($filtre->getEntrepot() != NULL)
		     // oui, je sais c'est pas facile à comprende ... ('ust' veux dire uniquement, sauf, tout)
		     // si on veut filter sur un autre critere faut changer getXXX() avec XXX nom de la colonne
		     $this->addCriteria('entrepot', array('colonne' => $filtre->getEntrepot()->getNom(),'ust' => $filtre->getEntrepotfiltre() ) );
		else
		     $this->addCriteria('entrepot', array('colonne' => NULL, 'ust' => $filtre->getEntrepotfiltre() ) );
		// réglage filtre Famille
		if ($filtre->getFamille() != NULL)
		     // ... et ça itou
		     $this->addCriteria('famille', array('colonne' => $filtre->getFamille()->getNom(),'ust' => $filtre->getFamillefiltre() ) );
		else
		     $this->addCriteria('famille', array('colonne' => NULL, 'ust' => $filtre->getFamillefiltre() ) );
		// réglage filtre Createur
		if ($filtre->getCreateur() != NULL)
		     // ... et ça itou
		     $this->addCriteria('createur', array('colonne' => $filtre->getCreateur()->getUsername(),'ust' => $filtre->getCreateurfiltre() ) );
		else
		     $this->addCriteria('createur', array('colonne' => NULL, 'ust' => $filtre->getCreateurfiltre() ) );
		// réglage filtre recherche d'une chaine
		if ($filtre->getNom() != NULL)
		     // ... et ça itou
		     $this->addCriteria('ref', array('colonne' => $filtre->getNom(),'ust' => $filtre->getNomfiltre() ) );
		else
		     $this->addCriteria('ref', array('colonne' => NULL, 'ust' => $filtre->getNomfiltre() ) );
   		// precise le repository et ce qu'on veut lister
		$this->aLister();
		// change de repository
		//$this->setRepositoryPath('SYM16SimpleStockBundle:ReferenceFiltre');
		// appel de la fonction mère
		return parent::filtrerAction($request);
	    }
	}
	//arrivé ici par GET : afficher le formulaire et le passer à la vue
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

	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_GESTIONNAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'GESTIONNAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Reference);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'une reference", new ReferenceType(array('em' => $this->stockconnection)) );
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_reference_modifier")
     * @Template()
     */
     /* Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")*/
    public function modifierAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_GESTIONNAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'GESTIONNAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'une reference", new ReferenceModifierType(array('em' => $this->stockconnection)) );
	// appel de la fonction mère
	return parent::modifierAction($request);
    }

    /**
     *
     * supprimer un article
     *
     * @Route("/suppr", name="sym16_simple_stock_reference_supprimer")
     */
    public function supprimerAction(Request $request) {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_GESTIONNAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'GESTIONNAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	$this->setMesgFlash('Référence bien supprimée');
	// appel de la fonction mère
	return parent::supprimerAction($request);
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
	//echo "<script>alert($sql)</script>";
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

    /**
     * D'après le code de Julien Cornu (merci !)
     * @Route("/reference-ajax1", name="reference_ajax1")
     */
    public function referenceAjax1Action(Request $request){
	// A des fins pédago on montre qu'on peut récupérer aussi bien pat GET que par POST
	if($request->getMethod() == 'POST')
	//récupération de l'id famille transmise par le script Ajax par POST
            $familleId = $request->request->get('famille_id');
	//récupération de l'id famille transmise par le script Ajax par GET
        else
	    $familleId = $request->query->get('famille_id');
	//récupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
	// création d'un objet rsm qui va recevoir les colonnes qui nous intéressent
        $rsm = new ResultSetMapping();
	// on indique qu'on veut récupérer la colonne id
        $rsm->addScalarResult('id', 'id');
	// on indique qu'on veut récupérer la colonne Nom (libellé de la table mySql)
        $rsm->addScalarResult('Nom', 'Nom');
	// construction de la requête SQL
        $sql = "SELECT * FROM Composant
                WHERE 1 = 1 ";
	// si familleId  est défini on continue la construction pat concaténation
        if($familleId != '')
            $sql .= " AND Composant.famille_id = :famille_id ";
	// on prépare la requête
        $query = $em->createNativeQuery($sql, $rsm);
	// si familleId est défini on  passe famille_id à la requête sqp
        if($familleId != '')
            $query->setParameter('famille_id', $familleId);
	// on récupère le résultat : les composants liés à cet famille
        $composants = $query->getResult();
	// on met le résultat dans un tableau
        $composantsList = array();
        foreach ($composants as $composant)
            array_push($composantsList, array('id' => $composant['id'], 'nom' => $composant['Nom']));
	// on renvoie le réponse au client  après encodage JSON
        return   new JsonResponse($composantsList);
    }
}
