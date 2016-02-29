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

    // récupère la première clé étrangère qui pointe sur une id
    private function getForeignKey($id)
    {
	//récupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
	//création d'un objet rsm qui va recevoir les colonnes qui nous intéressent
        $rsm = new ResultSetMapping();
	// on indique qu'on veut récupérer la colonne TABLE_NAME (la table propriétaire)
        $rsm->addScalarResult('TABLE_NAME', 'proprio');
	// on indique qu'on veut récupérer la colonne COLUMN_NAME (le nom de la clé_étrangère)
        $rsm->addScalarResult('COLUMN_NAME', 'keyname');
	// construction de la requête SQL
	$entityname = $this->getEntityName();
        $sql = "SELECT TABLE_NAME,COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE  REFERENCED_TABLE_NAME = '".$entityname."'";
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
	    $sql = "SELECT id FROM ".$proprio['proprio']." WHERE ".$proprio['keyname']." = ".$id." LIMIT 1;";
            $query = $em->createNativeQuery($sql, $rsm);
	    $foreignkeys = $query->getResult();
	    // de nouveau faut balayer même si on sait que la table n'a qu'une ligne a cause du LIMIT 1
	    foreach($foreignkeys as  $foreignkey)
	    	if($foreignkey != NULL)
	    	    //une clé etrangere pointant sur cette id a été trouvée
                    return array('proprio' => $proprio['proprio'], 'id' => $foreignkey['id']);
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

    //liste une table
    public function listerAction()
    {

	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
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
	$service = $this->container->get('sym16_simple_stock.lister_tout')->listerEntite($alister);
	//lister
	return $this->render($service['listtwig'], $service['tab']);
    }

    //liste une table sur laquelle on applique un filtre
    public function filtrerAction(Request $request)
    {
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
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
	// récupératioin de l'entité à hydrater
	$entity = $this->entityobject;
	// creation du formulaire
	$form = $this->createForm($this->formobject, $entity);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	// hydrater les variables $Reference
	    $form->bind($request);
	    // verifier la validité des valeurs d’entrée
	    if($form->isValid()) {
	        // enregistrer SReference dans la BDD
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();
		// affichage de la liste reactualisee
		return $this->listerAction();
	    }
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilsation de l'annotation Template()
    	return array('titre' => $this->formname, 'form' => $form->CreateView() );
    }

    public function modifierAction(Request $request)
    {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $entity = $em->getRepository($this->repositoryPath)->find($id);
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
		    $em->persist($entity);
		    $em->flush();
		    // affichage de la liste reactualisee
		    return $this->listerAction();
		}
	}
    	// On est arrivé par GET ou bien données d'entrées invalides
    	// avec l'utilisation de l'annotation Template()
    	return array('titre' => $this->formname, 'form' => $form->CreateView() );
    }

    //supprime une entité
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récupérartion de l'entite d'id  $id
        $entity = $em->getRepository($this->repositoryPath)->find($id);
	// vérifier si une entité est propriétaire de cette entité d'id=$id
	$foreignkey = $this->getForeignKey($id);
	if($foreignkey!= NULL){
 	    $nom = $entity->getNom();
	    $entiteproprio = $foreignkey['proprio'];
	    $idproprio = $foreignkey['id'];
	    echo "<script>alert(\"Suppresion refusée : $nom est utilisé par le/la/l\' $entiteproprio  d\'ID = $idproprio\")</script>";
	    return $this->listerAction();
	}
	// suppression de l'entité
	$em->remove($entity);
	$em->flush();
	// message flash
	$this->get('session')->getFlashBag()->add('info', $this->mesgflash);
	$this->get('session')->getFlashBag()->add('info', 'Presser F5 pour supprimer ce message');
	// affichage de la liste reactualisee
	return $this->listerAction();
    }
}
