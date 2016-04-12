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
use SYM16\SimpleStockBundle\Form\ArticleModifierType;
use SYM16\SimpleStockBundle\Form\ArticleFiltreType;
use SYM16\SimpleStockBundle\Entity\ArticleFiltre;
use SYM16\SimpleStockBundle\Entity\ArticlePrelevement;

/**
 *
 * Classe Article
 *
 * @Route("/article")
 */
class ArticleController extends /*Controller*/ SimpleStockController
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

	$this->setRepositoryPath('SYM16SimpleStockBundle:Article');
	$this
	    ->addColname('Réf',		'RefRef')
	    ->addColname('Nom',		'NomRef')
	    ->addColname('N° de série',	'Commentaire')
	    ->addColname('Qté',		'Quantite')
	    //->addColname('PrixHT',	'Prixht')
	    //->addColname('TVA',		'Tva')
	    //->addColname('Créateur',	'Createur')
	    //->addColname('Famille',	'Famille')
	    ->addColname('Entrepot',	'NomEntrepot') 
	    ->addColname('Famille',	'NomFamille') 
	    //->addColname('Emplacement', 'NomEmplacement')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_article_modifier',
            'supr'=> 'sym16_simple_stock_article_supprimer',
            'list'=> 'sym16_simple_stock_article_lister',
	    'prop'=> 'sym16_simple_stock_article_propriete',
	    'prel'=> 'sym16_simple_stock_article_prelever')
	);

	$this->addRoute('lister',		"sym16_simple_stock_article_lister")
	;

	$this->setListName("Liste des articles");

	//pour l'affichage des propriétés d'une entité
	$this->setPropertyName("Détails de l'Article :");
	$this
	    ->addProperty('Référence',			array('RefRef',		"%s"))
	    ->addProperty('Libellé',			array('NomRef', 	"%s"))
	    ->addProperty('N° de série',		array('Commentaire', 	"%s"))
	    ->addProperty('Quantité',			array('Quantite', 	"%5d"))
	    ->addProperty('Unité de vente',		array('Udv',	 	"%5d"))
	    ->addProperty('Prix hors taxe',		array('Prixht', 	"%5.2f"))
	    ->addProperty('TVA',			array('Tva',		"%5.2f"))
	    ->addProperty('Date d\'acquisition',	array('Dateacquisition',	NULL))
	    ->addProperty("Appartient à la famille",	array('NomFamille',	"%s"))
	    ->addProperty("Appartient au composant dans cette famille",	array('NomComposant',	"%s"))
	    ->addProperty("Stocké dans l'entrepot",	array('NomEntrepot',	"%s"))
	    ->addProperty("Rangé à l'emplacement dans cet entrepot",	array('NomEmplacement',	"%s"))
	    ->addProperty("Créateur de l'article",	array('Createur', 	"%s"))
	    ->addProperty('Date et heure de création',		array('Creation', 	NULL))
	    ->addProperty('Date et heure de modification',	array('Modification',	NULL))
	;
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_article_lister")
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
     * créer le pdf d'une liste
     *
     * @Route("/viewpdf", name="sym16_simple_stock_article_pdflister")
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
     * filtrer un tableau en faisant appel à un service
     *
     * @Route("/filter", name="sym16_simple_stock_article_filtrer")
     */
    public function filtrerAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_EXAMINATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'EXAMINATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de la classe de filtrage
	$filtre = new ArticleFiltre();
   	// precise le repository et ce qu'on veut lister
	$this->aLister();
	// creation du formulaire de saisie des paramètres du filtre
	$form = $this->createForm(new ArticleFiltreType(array('em' => $this->stockconnection)), $filtre);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	    // hydrater les variables ReferenceFiltre
	    $form->bind($request);
	    // verifier la validité des valeurs du formulaire
	    if($form->isValid()) {
		//ajoute des critères de filtrage (vanant du formulaire)
		// réglage filtre Reference
		if ($filtre->getReference() != NULL)
		     // oui, je sais c'est pas facile à comprende ... ('ust' veux dire uniquement, sauf, tout)
		     // si on veut filter sur un autre critere faut changer getXXX() avec XXX nom de la colonne
		     $this->addCriteria('reference', array('colonne' => $filtre->getReference()->getRef(),'ust' => $filtre->getReferencefiltre() ) );
		else
		     $this->addCriteria('reference', array('colonne' => NULL, 'ust' => $filtre->getReferencefiltre() ) );
		// réglage filtre Nom de la référence
		if ($filtre->getNom() != NULL)
		     // ... et ça itou
		     $this->addCriteria('nom', array('colonne' => $filtre->getNom()->getNom(),'ust' => $filtre->getNomfiltre() ) );
		else
		     $this->addCriteria('nom', array('colonne' => NULL, 'ust' => $filtre->getNomfiltre() ) );
		// réglage filtre Createur de l'Article
		if ($filtre->getCreateur() != NULL)
		     // ... et ça itou
		     $this->addCriteria('createur', array('colonne' => $filtre->getCreateur()->getUsername(),'ust' => $filtre->getCreateurfiltre() ) );
		else
		     $this->addCriteria('createur', array('colonne' => NULL, 'ust' => $filtre->getCreateurfiltre() ) );
		// réglage filtre recherche d'une chaine
		if ($filtre->getRecherche() != NULL)
		     // ... et ça itou
		     $this->addCriteria('commentaire', array('colonne' => $filtre->getRecherche(),'ust' => $filtre->getRecherchefiltre() ) );
		else
		     $this->addCriteria('commentaire', array('colonne' => NULL, 'ust' => $filtre->getRecherchefiltre() ) );
		// change de repository
		// appel de la fonction mère
		return parent::filtrerAction($request);
	    }
	}
	//arrivé ici par GET : afficher le formulaire et le passer à la vue
    	    return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig', 
		array('titre' => "Filtre d'affichage des articles", 'form' => $form->CreateView() )
	    );
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/property", name="sym16_simple_stock_article_propriete")
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
     * @Route("/add", name="sym16_simple_stock_article_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_GESTIONNAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'GESTIONNAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Article);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un article", new ArticleType(array('em' => $this->stockconnection)) );
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *
     * supprimer un article
     *
     * @Route("/suppr", name="sym16_simple_stock_article_supprimer")
     */
    public function supprimerAction(Request $request) {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_GESTIONNAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'GESTIONNAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	$this->setMesgFlash('Article bien supprimé');
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }

    /**
     *
     * modifier un article dans l'entité (avec formulaire externalisé)
     *
     * @Route("/mod", name="sym16_simple_stock_article_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_GESTIONNAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'GESTIONNAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un article", new ArticleModifierType(array('em' => $this->stockconnection)) );
	// appel de la fonction mère
	return parent::modifierAction($request);
    }

    /**
     *
     * prélever  une article dans le stocl
     *
     * @Route("/prel", name="sym16_simple_stock_article_prelever")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function preleverAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_GESTIONNAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'GESTIONNAIRE', 'homepath' => "sym16_simple_stock_homepage"));

	// récuprération du service session
	$session = $this->get('session');
	// récupération de la variable de session contenant le nom interne de la connection à la BDD courante
	$stockconnection = $session->get('stockconnection');
	// récuperation entity manager
	$em = $this->getDoctrine()->getManager($stockconnection);
	// récuparation du repository
	$rep = $em->getRepository('SYM16SimpleStockBundle:Article');
	// récupe de l'id de l'article à prélever
        $id = $request->query->get('valeur');
        //récupérartion de l'entite d'id  $id
        $entity = $rep->find($id);
	//récupération de la référence
	$ref = $entity->getRefRef();
	// calcul de la quantité totale disponible pour la référence donnée
	$quantitetotale = $rep->getQteTotale($ref);
	// Creation du forulaire de prélèvement
	$articleprelevement = new ArticlePrelevement($quantitetotale);
	$formBuilder = $this->createFormBuilder($articleprelevement);
	// ajout des champs de formulaire
	$formBuilder
	    ->add('quantite',	'number', array('label' => "Quantité à prélever (".$quantitetotale." max.) : ") )
	;
	// génération du formulaire
	$form = $formBuilder->getForm();

	// test si on arrive par POST
	$request = $this->getRequest();
	if($request ->getMethod() == 'POST') {
	    $form->bind($request);
	    // vérification des valeurs du formulaire
	    if($form->isValid()){
		// y a plus qu'a prélever
		$aprelever = $articleprelevement->getQuantite();
		$entities = $rep->findByRef($ref);
		// on parcourt les entités de référence à prélever
		foreach($entities as $entity){
		    // si la qté à prélever est sup à la quantité dispo dans l'article on supprime l'article
		    if($aprelever >= $entity->getQuantite()){
			$aprelever = $aprelever - $entity->getQuantite();
			$em->remove($entity); 
		    }
		    // sinon on actualise la quantité restante
		    else{
			$entity->setQuantite($entity->getQuantite()-$aprelever);
		   	$em->persist($entity);
			// et on arrête de parcourir les entités
			break;
		   }
		}
		$em->flush();
		// test si le reliquat est supérieur ou égal au seuil d'alerte
		if($entity->getQuantite() >= $entity->getSeuil())
		   // si oui, on se contente d'afficher la liste des articles (provisoirement, ensuite prévoir un confirmation avec reliquat)
 		   return $this->redirect($this->generateUrl("sym16_simple_stock_article_lister"));
		else
		   // si non, on envoie un mail d'alerte
 		   //return $this->redirect($this->generateUrl("sym16_simple_stock_article_lister"));
		   return $this->redirect($this->generateUrl("sym16_simple_stock_mail_asb", 
			array(
			'reference' => $ref,
			'reliquat' => $entity->getQuantite(),
			'seuil' => $entity->getSeuil(),
			'createur' => $entity->getCreateur(),
			//'route' => $this->generateUrl("sym16_simple_stock_article_lister") )) 
			'route' => "sym16_simple_stock_article_lister") ) 
		   );
	    }
	}

	// si on est là c'est qu'on est arrivé par GET
	//affichage du formulaire
	return $this->render(
		'SYM16SimpleStockBundle:Forms:simpleform.html.twig',
		array('titre' => "Prélèvement d'articles référencés : ".$ref." -  Quantité max. autorisée : ".$quantitetotale,
			'form' => $form->CreateView()
		)
	);
    }
}

