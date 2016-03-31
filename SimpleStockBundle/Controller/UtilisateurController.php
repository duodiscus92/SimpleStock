<?php
// src/SYM16/SimpleStockBundle/Controller/UtilisateurController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\UserBundle\Entity\User;
use SYM16\UserBundle\Form\UserType;
use SYM16\UserBundle\Form\UserModifierType;
use SYM16\UserBundle\Form\UserModifierMoiType;
use SYM16\UserBundle\Form\UserChangerMdpType;
use SYM16\UserBundle\Form\UserOubliMdpType;
use SYM16\UserBundle\Form\UserInscriptionType;
use  SYM16\SimpleStockBundle\Controller\OubliMdp;
/**
 *
 * Classe Utilisateur
 *
 * @Route("/utilisateur")
 */
class UtilisateurController extends /*Controller*/ SimpleStockController
{

    private $stockconnection='stockmaster';
    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	// change de database donc d'entity manager
	$this->setEmName($this->stockconnection);

	$this->setRepositoryPath('SYM16UserBundle:User');
	$this
	    ->addColname('Nom',		'Nom')
	    ->addColName('Prénom',	'Prenom')
	    ->addColName('Login',	'Username')
	    ->addColName('Mail',	'Email')
	    ->addColName('Statut',	'Statut')
	    ->addColName('Adr',		'Adr')
	    //->addColName('Créateur',	'Createur')
	    //->addColName('Création',	'Creation')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_utilisateur_modifier',
            'supr'=> 'sym16_simple_stock_utilisateur_supprimer',
	    'list'=> 'sym16_simple_stock_utilisateur_lister',
	    'prop'=> 'sym16_simple_stock_utilisateur_propriete')
	);

        $this
	    ->addRoute('lister',               "sym16_simple_stock_utilisateur_lister")
        ;

	$this->setListName("Liste des utilisateurs");

	//pour l'affichage des propriétés d'une entité
	$this->setPropertyName("Détail de l'Utilisateur :");
	$this
	    ->addProperty('Nom de l\'Utilisateur',			array('Nom', 		"%s"))
	    ->addProperty('Prénom de l\'Utilisateur',			array('Prenom',		"%s"))
	    ->addProperty('Identifiant de connexion',			array('Username',	"%s"))
	    //->addProperty('Mot de passe',				array('Password', 	"%s"))
	    ->addProperty('Statut',					array('Statut',	 	"%s"))
	    ->addProperty('Email',					array('Email',	 	"%s"))
	    ->addProperty('Mail d\'alerte seuil bas',			array('Asb',	 	"%d"))
	    ->addProperty('Mail d\'alerte changement de paramètre',	array('Acp',	 	"%d"))
	    ->addProperty('Mail d\'alerte dépôt/prélèvement',		array('Adr',	 	"%d"))
	    ->addProperty('Créateur du l\'Utilisateur',			array('Createur',        "%s"))
	    ->addProperty('Date de création',				array('Creation', 	NULL))
	    ->addProperty('Date de modification',			array('Modification',	NULL))
	;
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_utilisateur_lister")
     */
    public function listerAction()
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'ADMINISTRATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::listerAction();
    }

    /**
     * affcicher le proprité d'un item 
     *
     * @Route("/property", name="sym16_simple_stock_utilisateur_propriete")
     */
    public function proprieteAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_TEMPORAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'TEMPORAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// precise le repository ainsi que les propriétés à afficher
	 $this->aLister();
	// appel de la fonction mère
	return parent::proprieteAction($request);
    }

    /**
     *  ajouter un article dans l'entité à partir d'un formulaire externalisé
     *
     * @Route("/add", name="sym16_simple_stock_utilisateur_ajouter")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function ajouterAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new User);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'un utilisateur", new UserType(array('em' => $this->stockconnection) ));
    	// appel de la fonction mère
    	return parent::ajouterAction($request);
    }

    /**
     *  S'inscrire : c'est presque comme ajouter sauf que le role est TEMPORAIRE
     *
     * @Route("/inscr", name="sym16_simple_stock_utilisateur_sinscrire")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function sinscrireAction(Request $request)
    {
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new User);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
	// creation du formulaire
	$this->setFormNameAndObject("Formulaire d'inscription", new UserInscriptionType(array('em' => $this->stockconnection)) );
    	// appel de la fonction mère
    	return parent::sinscrireAction($request);
    }

    /**
     *  supprimer un article
     *
     * @Route("/del", name="sym16_simple_stock_utilisateur_supprimer")
     */
    public function supprimerAction(Request $request) {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// precsier le repository et ce qu'on veut lister après suppression
	$this->aLister();
	// message flash
	//$this->setMesgFlash('Composant bien supprimé');
	// appel de la fonction mère
	return parent::supprimerAction($request);
    }

    /**
     *   modifier un article dans l'entité (avec formulaire externalisé
     *
     * @Route("/mod", name="sym16_simple_stock_utilisateur_modifier")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_SUPER_UTILISATEUR'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'SUPER UTILISATEUR', 'homepath' => "sym16_simple_stock_homepage"));
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'un utilisateur", new UserModifierType(array('em' => $this->stockconnection) ));
	// appel de la fonction mère
	return parent::modifierAction($request);
    }

    /**
     *   modifierMoi c'est comme modifier mais uniquement pour l'utilisateur courant
     *
     * @Route("/modme", name="sym16_simple_stock_utilisateur_modifiermoi")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function modifierMoiAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_TEMPORAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'TEMPORAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Mise à jour de mes données personnelles", new UserModifierMoiType(array('em' => $this->stockconnection) ));
	// appel de la fonction mère
	return parent::modifierMoiAction($request);
    }

    /**
     *   changer mot de passe
     *
     * @Route("/modmdp", name="sym16_simple_stock_utilisateur_modifiermdp")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function changerMdpAction(Request $request)
    {
	// contrôle d'accès
	if(!$this->get('security.context')->isGranted('ROLE_TEMPORAIRE'))
	    return $this->render('SYM16SimpleStockBundle:Common:alertaccessdenied.html.twig', 
		array('statut' => 'TEMPORAIRE', 'homepath' => "sym16_simple_stock_homepage"));
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Changer mon mot de passe", new UserChangerMdpType(array('em' => $this->stockconnection) ));
	// appel de la fonction mère
	return parent::changerMdpAction($request);
    }

    public function oubliMdpOldAction(Request $request)
    {
	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new OubliMdp);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
	// creation du formulaire
	$this->setFormNameAndObject("Mot de passe oublié", new UserOubliMdpType(array('em' => $this->stockconnection)) );
    	// appel de la fonction mère
    	return parent::oubliMdpAction($request);
    }

    /**
     *  Mot de passe oublié
     *
     * @Route("/oublimdp", name="sym16_simple_stock_utilisateur_oublimdp")
     * @Template("SYM16SimpleStockBundle:Forms:simpleform.html.twig")
     */
    public function oubliMdpAction(Request $request)
    {
	// récupération de l'entité à hydrater
	$entity = new OubliMdp;
	// creation du formulaire
	$form = $this->createFormBuilder($entity)
            ->add('username', 	'text', array('label' => 'Identifiant de connection') )
            ->add('email', 	'email', array('label' => 'Mail') )
	    ->getForm()
	;
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Reference
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
		// récupération de l'entity manager
		$em = $this->getDoctrine()->getManager('stockmaster');
		// username et mail fourni pour valider l'envoi d'un nouveau mdp
		$givenmail = $entity->getEmail();
		$givenusername = $entity->getUsername();
		// verifier si username et email existent dans la BDD
		$rep = $em->getRepository("SYM16UserBundle:User");
		if(($user = $rep->findOneBy(array('username' => $givenusername, 'email' => $givenmail) ))!= NULL){
		   // oui ils existent
		   $id = $user->getId();
		   // on met le flag oubli à pour garder en mémoire le fait qu'il faudra changer le mdp à la prochaine connexion
		   $user->setFlagoubli(1);
		   $session = $this->get('session');
		   $session->set('flagoubli', 1); 
	           // mettre à jour l'entité dans la BDD, donc on persiste le flag oubli
		   $em->persist($user);
		   $em->flush();
		   // et on se branche vers le mailer pour qu'il envoie le mdp
		   return $this->redirect($this->generateUrl("sym16_simple_stock_mail_mdpenvoi", 
			array('id' => $id)) ); 
	    	}
		// sinon retour page d'acceuil
		else
	    	   return $this->render('SYM16SimpleStockBundle:Common:alertoublimdpfail.html.twig', 
			array('homepath' => "sym16_simple_stock_homepage"));
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilsation de l'annotation Template()
    	return array('titre' => "Recevoir un nouveau mot de passe", 'form' => $form->CreateView() );
    }
}
