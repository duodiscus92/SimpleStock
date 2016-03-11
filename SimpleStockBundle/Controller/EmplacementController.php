<?php
//src/SYM16/SimpleStockBundle/Controller/EmplacementController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\SimpleStockBundle\Entity\Emplacement;
use SYM16\SimpleStockBundle\Form\EmplacementType;
//use SYM16\SimpleStockBundle\Form\EmplacementModifierType;

/**
 *
 * Classe Emplacement
 *
 * @Route("/emplacement")
 */
class EmplacementController extends /*Controller*/ SimpleStockController
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

	$this->setRepositoryPath('SYM16SimpleStockBundle:Emplacement');
	$this
	    ->addColname('Emplacement',	'Nom')
	    ->addColName('Entrepot',	'NomEntrepot')
	    ->addColName('Créateur',	'Createur')
	    ->addColName('Création',	'Creation')
	    ->addColName('Modification','Modification')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_emplacement_modifier',
            'supr'=> 'sym16_simple_stock_emplacement_supprimer',
	    'list'=> 'sym16_simple_stock_emplacement_lister',
	    'prop'=> 'sym16_simple_stock_emplacement_propriete')
	);


        $this->addRoute('lister',               "sym16_simple_stock_emplacement_lister")
        ;

	$this->setListName("Liste des emplacements");

	//pour l'affichage des propriétés d'une entité
	$this->setPropertyName("Détail de l'Emplacement :");
	$this
	    ->addProperty('Nom de l\'Emplacement',	array('Nom', 		"%s"))
	    ->addProperty('Rattaché à l\'Entrepot',	array('Nomentrepot',	"%s"))
	    ->addProperty('Créateur du l\'Emplacement',	array('Createur', 	"%s"))
	    ->addProperty('Date de création',		array('Creation', 	NULL))
	    ->addProperty('Date de modification',	array('Modification',	NULL))
	;
    }


    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_emplacement_lister")
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
     * affcicher le proprité d'un item 
     *
     * @Route("/property", name="sym16_simple_stock_emplacement_propriete")
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
     *
     * ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_emplacement_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Emplacement);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un emplacement", new EmplacementType(array('em' => $this->stockconnection)) );
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * supprimer un article
     *
     * @Route("/suppr", name="sym16_simple_stock_emplacement_supprimer")
     */
    public function supprimerAction(Request $request) {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	//$this->setMesgFlash('Composant bien supprimé');
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_emplacement_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un emplacement", new EmplacementType(array('em' => $this->stockconnection)) );
	// appel de la fonction mère
	return parent::modifierAction($request);
    }
}
