<?php
//src/SYM16/SimpleStockBundle/Controller/SimpleStockController.php
namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class SimpleStockController extends Controller
{


    private $repositoryPath;
    private $listColnames=array('id' => 'Id');
    private $modsupr;
    private $listname;
    private $formname;
    private $formobject;
    private $entityobject;
    private $mesgflash;
    private $filtre;
    private $listcriteria=array();
    private $propertyname;
    private $listProperties=array('id' => array('Id', "%3d"));
    private $emname = 'default'; //entity manager name
    private $listroute=array();

    // récupère la première clé étrangère qui pointe sur une id
    private function getForeignKey($id)
    {
	//récupération de l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
	//création d'un objet rsm qui va recevoir les colonnes qui nous intéressent
        $rsm = new ResultSetMapping();
	// on indique qu'on veut récupérer la colonne TABLE_NAME (la table propriétaire)
        $rsm->addScalarResult('TABLE_NAME', 'proprio');
	// on indique qu'on veut récupérer la colonne COLUMN_NAME (le nom de la clé_étrangère)
        $rsm->addScalarResult('COLUMN_NAME', 'keyname');
	// on indique qu'on veut récupérer la colonne CONSTRAINT_SCHEMA_NAME (le nom de la BDD où la clé se trouve)
        $rsm->addScalarResult('CONSTRAINT_SCHEMA', 'dbname');
	// récupération nom interne de la bdd courante
	// récuprération du service session
	$session = $this->get('session');
	// récupération de la variable de session contenant le nom interne de la BDD courante
	$currentdbname = $session->get('stockname');
	//$currentdbname = 'simplestock';
	$entityname = $this->getEntityName();
	// construction de la requête SQL
        $sql = "SELECT TABLE_NAME,COLUMN_NAME, CONSTRAINT_SCHEMA FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE"
		." WHERE  REFERENCED_TABLE_NAME = '".$entityname.
		"' AND CONSTRAINT_SCHEMA = '".$currentdbname."';";
	// on prépare la requête
        $query = $em->createNativeQuery($sql, $rsm);
	// on récupère le résultat : liste d'entité propriétaires et clé etrangère pointant sur cette entité
        $proprios = $query->getResult();
	// on balaye la liste pour trouver s'il y a une clé étrangère qui concerne cette id 
	unset($rsm);
        foreach ($proprios as $proprio){
	    //requete pour trouver la premiere clé etrangère qui pointe sur notre id
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('id', 'id');
            //$rsm->addScalarResult('Nom', 'nom');
	    $sql = "SELECT id /*, Nom*/ FROM ".$proprio['proprio']." WHERE ".$proprio['keyname']." = ".$id." LIMIT 1;";
            $query = $em->createNativeQuery($sql, $rsm);
	    $foreignkeys = $query->getResult();
	    // de nouveau faut balayer même si on sait que la table n'a qu'une ligne a cause du LIMIT 1
	    foreach($foreignkeys as  $foreignkey)
	    	if($foreignkey != NULL)
	    	    //une clé etrangere pointant sur cette id a été trouvée
                    return array('proprio' => $proprio['proprio'], 'id' => $foreignkey['id']/*, 'nom' => $foreignkey['nom']*/);
	    unset($rsm);
	}
	// on arrive ici c'est qu'aucune clé étrangere pointe sur cet id
	return NULL;
    }

    protected function setRepositoryPath($path)
    {
	$this->repositoryPath = $path;
	return $this;
    }

    protected function getEntityName()
    {
	return substr($this->repositoryPath, strpos($this->repositoryPath, ':')+1);
    }

    protected function addColname($tag, $item)
    {
	$this->listColnames[$tag]= $item;
	return $this;
    }

    protected function setModSupr($routes)
    {
	$this->modsupr = $routes;
	return $this;
    }

    protected function setListName($name)
    {
	$this->listname = $name;
    }

    protected function setFormNameAndObject($name, $object)
    {
	$this->formname = $name;
	$this->formobject = $object;
    }

    protected function setEntityObject($object)
    {
	$this->entityobject = $object;
	//initialisation du createur (l'utilisateur courant)
	$this->entityobject->setCreateur($this->get('session')->get('stockuser'));
    }

    protected function setMesgFlash($mesg)
    {
	$this->mesgflash = $mesg;
    }

    protected function addCriteria($key, $criteria)
    {
	$this->listcriteria[$key]= $criteria;
	return $this;
    }

    protected function setPropertyName($name)
    {
	$this->propertyname = $name;
    }

    protected function addProperty($key, $property)
    {
	$this->listProperties[$key] = $property;
	return $this;
    }

    protected function  setEmName($name)
    {
	$this->emname = $name;
    }

    protected function addRoute($key, $route)
    {
	$this->listroute[$key] = $route;
	return $this;
    }

    // merci à Olivier Laviale www.weirdog.com/
    protected function wd_remove_accents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
    	$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    	$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

    	return $str;
    }

    //liste une table
    public function listerAction()
    {
	/*$session = $this->get('session');
	$id = $session->get('stockuserid');
	if($session->get('flagoubli') == 1)
	    return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );*/
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
	// on récupère tout le contenu de la table
	$repository = $em->getRepository($this->repositoryPath);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
	// si la table est vide
	if ($entities == NULL)
	    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
	//  nombre total de lignes dans la table
	$totalusers = $repository->getNbItems();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $this->listColnames, 'entities' => $entities, 
			'path' => $this->modsupr, 'totalusers' => $totalusers, 'listname' => $this->listname);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister, 'screen');
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    // crée un pdf d'une table
    public function pdflisterAction()
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
	// on récupère tout le contenu de la table
	$repository = $em->getRepository($this->repositoryPath);
	// on récupère le contenu de la table
	$entities = $repository->findAll();
	// si la table est vide
	if ($entities == NULL)
	    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
	//  nombre total de lignes dans la table
	$totalusers = $repository->getNbItems();
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $this->listColnames, 'entities' => $entities, 
			'path' => $this->modsupr, 'totalusers' => $totalusers, 'listname' => $this->listname);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister, 'pdf');
	//lister
	$html =  $this->render($service['listtwig'], $service['tab']);
        //on instancie la classe Html2Pdf_Html2Pdf en lui passant en paramètre
        //le sens de la page "portrait" => p ou "paysage" => l
        //le format A4,A5...
        //la langue du document fr,en,it...
        $html2pdf = new \Html2Pdf_Html2Pdf('L','A4','fr');

        //SetDisplayMode définit la manière dont le document PDF va être affiché par l’utilisateur
        //fullpage : affiche la page entière sur l'écran
        //fullwidth : utilise la largeur maximum de la fenêtre
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('real');

        //writeHTML va tout simplement prendre la vue stocker dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);

        //Output envoit le document PDF au navigateur internet avec un nom spécifique 
	//qui aura un rapport avec le contenu à convertir (exemple : Facture, Règlement…)
        $html2pdf->Output($this->wd_remove_accents($this->listname.'.pdf'), 'D');
        return new Response();
    }

    // propriété d'une entité
    public function proprieteAction(Request $request)
    {
	$session = $this->get('session');
	$id = $session->get('stockuserid');
	if($session->get('flagoubli') == 1)
	    return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
        //récupérartion de l'entite d'id  $id
        $entity = $em->getRepository($this->repositoryPath)->find($id);
	// inutile de tester entity ==NULL car ça ne peut pas se produire
	$listArticles=array();
	foreach ($this->listProperties as $key => $property){
	    //on reconstruit les getters à partir des noms de colonnes
	    $getter = 'get'.$property[0];
	    //on obtient la valeur
	    $valeur = $entity->$getter();
	    //on test son type
	    if(is_object($valeur))
		$listArticles[$key] = $valeur->format('Y-m-d H:i:s');
	    else
	    	$listArticles[$key] = sprintf($property[1], $valeur);
	}
	return $this->render('SYM16SimpleStockBundle:Common:property.html.twig', 
	    array('propertyname' => $this->propertyname, 'valeurs' => $listArticles));
    }

    //liste une table sur laquelle on applique un filtre
    public function filtrerAction(Request $request)
    {
	$session = $this->get('session');
	$id = $session->get('stockuserid');
	if($session->get('flagoubli') == 1)
	    return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
	// on récupère tout le repository
	$repository = $em->getRepository($this->repositoryPath);
	$uniquement = array(); $sauf = array(); 
	// Construction du filtre
	foreach($this->listcriteria as $key => $criteria){
	    $uniquement[$key] = $sauf[$key] = NULL;
	    if ($criteria['ust'] == 'u')
		$uniquement[$key] = $criteria['colonne'];
	    else if($criteria['ust'] == 's')
		$sauf[$key] = $criteria['colonne'];
	}
	// On récupère le contenu de la table filtrée
	$entities=$repository->findByFilter(array('u' => $uniquement, 's' => $sauf) );
	// si la table est vide
	if ($entities == NULL)
	    return $this->render('SYM16SimpleStockBundle:Common:nolist.html.twig');
	// nombre total de lignes dans la table
	$totalusers = $repository->countByFilter(array('u' => $uniquement, 's' => $sauf));
	//on place tous les paramètres à lister dans un tableau
	$alister = array('listcolnames' => $this->listColnames, 'entities' => $entities, 
			'path' => $this->modsupr, 'totalusers' => $totalusers, 'listname' => $this->listname);
	// récupération du service et de la prestation  "lister_tout"
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    //ajoute une entite
    public function ajouterAction(Request $request){
	$session = $this->get('session');
	$id = $session->get('stockuserid');
	if($session->get('flagoubli') == 1)
	    return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );
	// récupératioin de l'entité à hydrater
	$entity = $this->entityobject;
	$entity->setCreation(new \DateTime() );
	$entity->setModification(new \DateTime() );
	// creation du formulaire
	$form = $this->createForm($this->formobject, $entity);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Reference
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer SReference dans la BDD
		$em = $this->getDoctrine()->getManager($this->emname);
		$em->persist($entity);
		$em->flush();
		// affichage de la liste reactualisee
		$listerRoute = $this->listroute['lister'];
		if($listerRoute == NULL) 
		    return $this->listerAction();
		else
		    //return $this->redirect($this->generateUrl($listerRoute));
		    $item = substr(strrchr($this->repositoryPath, ':'), 1);
		    $objet = $item == 'Article' ? $entity->getNomRef() : $entity->getNom();
		    if($item == 'Article')
		        return $this->redirect($this->generateUrl("sym16_simple_stock_mail_adr", 
			    array('item' => $item, 'nature' => 'dépôt', 
			      //'objet' => $entity->getNom(), 
			      'objet' =>  $objet,
			      'createur' => $entity->getCreateur(), 'route' => $listerRoute )) );
		    else
		        return $this->redirect($this->generateUrl("sym16_simple_stock_mail_ams", 
			    array('item' => $item, 'nature' => 'ajout', 
			      //'objet' => $entity->getNom(), 
			      'objet' =>  $objet,
			      'createur' => $entity->getCreateur(), 'route' => $listerRoute )) );
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilsation de l'annotation Template()
    	return array('titre' => $this->formname, 'form' => $form->CreateView() );
    }

    //special pour le formulaire d'insciption
    public function sinscrireAction(Request $request){
	// récupératioin de l'entité à hydrater
	$entity = $this->entityobject;
	$entity->setCreation(new \DateTime() );
	$entity->setModification(new \DateTime() );
	// creation du formulaire
	$form = $this->createForm($this->formobject, $entity);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Reference
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer SReference dans la BDD
		$em = $this->getDoctrine()->getManager($this->emname);
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl("sym16_simple_stock_mail_confreg", array('id' => $entity->getId())) );
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilsation de l'annotation Template()
    	return array('titre' => $this->formname, 'form' => $form->CreateView() );
    }

    // modifier une entité
    public function modifierAction(Request $request)
    {
	$session = $this->get('session');
	$id = $session->get('stockuserid');
	if($session->get('flagoubli') == 1)
	    return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
        //récuparartion de l'entite d'id  $id
        $entity = $em->getRepository($this->repositoryPath)->find($id);
	$entity->setModification(new \DateTime() );
	// creation du formulaire
	$form = $this->createForm($this->formobject, $entity);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Reference
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Reference dans la BDD
		    //$em->persist($entity);
		    $em->flush();
		    // affichage de la liste reactualisee
		    $listerRoute = $this->listroute['lister'];
		    if($listerRoute == NULL) 
		    	return $this->listerAction();
		    else
		    	//return $this->redirect($this->generateUrl($listerRoute));
		        $item = substr(strrchr($this->repositoryPath, ':'), 1);
			$objet = $item == 'Article' ? $entity->getNomRef() : $entity->getNom();
		        return $this->redirect($this->generateUrl("sym16_simple_stock_mail_ams", 
			    array('item' => $item, 'nature' => 'modification', 
				  //'objet' => $entity->getNom(),
				  'objet' =>  $objet,
                                  'createur' => $entity->getCreateur(), 'route' => $listerRoute )) );
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilisation de l'annotation Template()
    	return array('titre' => $this->formname, 'form' => $form->CreateView() );
    }

    //supprime une entité
    public function supprimerAction(Request $request) {
	$session = $this->get('session');
	$id = $session->get('stockuserid');
	if($session->get('flagoubli') == 1)
	    return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
        //récupérartion de l'entite d'id  $id
        $entity = $em->getRepository($this->repositoryPath)->find($id);
	// vérifier si une entité est propriétaire de cette entité d'id=$id
	$foreignkey = $this->getForeignKey($id);
	if($foreignkey!= NULL){
 	    $nom = $entity->getNom();
	    $entiteproprio = $foreignkey['proprio'];
	    $idproprio = $foreignkey['id'];
	    //$nomproprio = $foreignkey['nom'];
	    // afficher un boite d'alerte
	    return $this->render('SYM16SimpleStockBundle:Common:alertsuppr.html.twig', 
		array('path' => $this->modsupr, 'nom' => $nom, 'entite' => $entiteproprio, 'id' => $idproprio/*, 'nomproprio' => $nomproprio*/));
	}
	// suppression de l'entité
	$em->remove($entity);
	$em->flush();
	// affichage de la liste reactualisee
	$listerRoute = $this->listroute['lister'];
	if($listerRoute == NULL) 
	    return $this->listerAction();
	else
	   // return $this->redirect($this->generateUrl($listerRoute));
	   $item = substr(strrchr($this->repositoryPath, ':'), 1);
	   $objet = $item == 'Article' ? $entity->getNomRef() : $entity->getNom();
	   return $this->redirect($this->generateUrl("sym16_simple_stock_mail_ams", 
	       array('item' => $item, 'nature' => 'suppression', 
		     //'objet' => $entity->getNom(),
		     'objet' => $objet, 
                     'createur' => $entity->getCreateur(), 'route' => $listerRoute )) );
    }

    // modifier mes données personnelles (n'est utilisée que par UtilisateurController)
    public function modifierMoiAction(Request $request)
    {
	$session = $this->get('session');
	$id = $session->get('stockuserid');
	if($session->get('flagoubli') == 1)
	    return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );
	// récupe de l'id de l'article modifier
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
        //récuparartion de l'entite d'id  $id
        $entity = $em->getRepository($this->repositoryPath)->find($id);
	$entity->setModification(new \DateTime() );
	// creation du formulaire
	$form = $this->createForm($this->formobject, $entity);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater la variable $Reference
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    // enregistrer Reference dans la BDD
		    //$em->persist($entity);
		    $em->flush();
		    // affichage des propriétés réactualisées
		    return $this->proprieteAction($request);
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilisation de l'annotation Template()
    	return array('titre' => $this->formname, 'form' => $form->CreateView() );
    }

    // changer mon mot de passe (n'est utilisée que par UtilisateurController)
    public function changerMdpAction(Request $request)
    {
	// récupe de l'id de l'article modifier
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager($this->emname);
        //récuparartion de l'entite d'id  $id
        $entity = $em->getRepository($this->repositoryPath)->find($id);
	$entity->setModification(new \DateTime() );
	// récuperer le mdp de l'ego utilisateur
	$password = $entity->getPassword();
	// récuperer le username de l'ego utilisateur puis le mettre à NULL
	$username = $entity->getUsername();
	$entity->setUsername(NULL);
	// creation du formulaire de changement de mdp : usernam, ancien mdp et nouveau mdp 2 fois
	$form = $this->createForm($this->formobject, $entity);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// on est donc arrivé en ce point par POST
	// hydrater l'entité 
		$form->bind($request);
		// verifier la validité des valeurs d’entrée
		if($form->isValid()) {
		    //récupérer l'ancien mdp (non codé)  fourni par l'utilisateur
		    $givenoldpassword = $entity->getOldpassword();
		    //récupérer le username fourni par l'utilisateur
		    $givenusername = $entity->getUsername();
		    // vérifier que le username fourni est bien celui de l'ego utilisateur
		    if($givenusername == $username){
			//vérifier que mdp non codé fourni est équivaleent au mdp de l'égo codé
			if(password_verify($givenoldpassword, $password)){
			    // C'est oui, alors on peut enregistrer le nouveau mdp
			    // mais avant on signale que le mot de passe oublié  a été changé (au cas où)
	        	    $entity->setFlagoubli(0);
		    	    $em->flush();
		    	    // et pour finir, boite de dialoguer info et retour page acceuil
	    		   return $this->render('SYM16SimpleStockBundle:Common:infochpwddone.html.twig', 
				array('homepath' => "sym16_simple_stock_homepage"));
			}
		    }
		}
		// on arrive ici c'est que le mdp et/ou le username fourni ne sont pas ceux de l'égo
	    	return $this->render('SYM16SimpleStockBundle:Common:alertchpwdfail.html.twig', 
			array('homepath' => "sym16_simple_stock_homepage"));
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilisation de l'annotation Template()
    	return array('titre' => $this->formname, 'form' => $form->CreateView() );
    }
}
