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

    //permet de paramétrer ce qu'on veut lister
    private function aLister()
    {
	$this->setRepositoryPath('SYM16SimpleStockBundle:Reference');
	$this
	    ->addColname('Réf',		'Ref')
	    ->addColname('Libellé',	'Nom')
	    ->addColname('UDV',		'Udv')
	    ->addColname('Seuil',	'Seuil') 
	    ->addColname('Créateur',	'Createur') 
	    ->addColname('Entrepot',	'NomEntrepot')
	    ->addColname('Emplacement',	'NomEmplacement')
	;

	$this->setModSupr(array(
            'mod' => 'sym16_simple_stock_reference_modifier',
            'supr'=> 'sym16_simple_stock_reference_supprimer')
	);

	$this->setListName("Liste des références");
    }

    /**
     * lister un tableau en faisant appel à un service
     *
     * @Route("/view", name="sym16_simple_stock_reference_lister")
     */
    public function listerAction()
    {
	// precise le repository et ce qu'on veut lister
	 $this->aLister();
	// appel de la fonction mère
	return parent::listerAction();
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
	// creation du formulaire de saisie des parapètres du filtre
	$form = $this->createForm(new ReferenceFiltreType, $filtre);
	// test de la méthode
	if($request->getMethod() == 'POST'){
	    // hydrater les variables ReferenceFiltre
	    $form->bind($request);
	    // verifier la validité des valeurs du formulaire
	    if($form->isValid()) {
		//ajoute des critères de filtrage (vanant du formulaire)
		if ($filtre->getEntrepot() != NULL)
		     // oui, je sais c'est pas facile à comprende ... ('ust' veux dire uniquement, sauf, tout)
		     // si on veut filter sur un autre critere faut changer getXXX() avec XXX nom de la colonne
		     $this->addCriteria('entrepot', array('colonne' => $filtre->getEntrepot()->getNom(),'ust' => $filtre->getEntrepotfiltre() ) );
		else
		     $this->addCriteria('entrepot', array('colonne' => NULL, 'ust' => $filtre->getEntrepotfiltre() ) );
		if ($filtre->getCreateur() != NULL)
		     // ... et ça itou
		     $this->addCriteria('createur', array('colonne' => $filtre->getCreateur()->getLogin(),'ust' => $filtre->getCreateurfiltre() ) );
		else
		     $this->addCriteria('createur', array('colonne' => NULL, 'ust' => $filtre->getCreateurfiltre() ) );
   		// precise le repository et ce qu'on veut lister
		$this->aLister();
		// change de repository
		$this->setRepositoryPath('SYM16SimpleStockBundle:ReferenceFiltre');
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

	// creation d'une instance de l'entité propriétaire a hydrater
	$this->setEntityObject(new Reference);
	// creation du formulaire
	$this->setFormNameAndObject("Ajout d'une reference", new ReferenceType);
	// preciser le repository ce qu'on veut lister après ajout
	$this->aLister();
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
	// préciser le formulaire à créer
	$this->setFormNameAndObject("Modification d'une reference", new ReferenceModifierType);
	// preciser le repository et ce qu'on veut lister après modification
	$this->aLister();
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
