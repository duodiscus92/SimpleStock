<?php

namespace SYM16\SimpleStockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\HttpFoundation\Request;
use SYM16\SimpleStockBundle\Entity\Stocklist;
use SYM16\SimpleStockBundle\Entity\Locator;

/**
 * Classe Acceuil
 *
 * @Route("/home")
 *
 */
class AcceuilController extends Controller
{
    /**
    *
    * @Route("/", name="sym16_simple_stock_homepage")
    */ 
    public function indexAction(/*$name*/)
    {
	//récupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
	// récupération de l'utilisateur courant
	$user = $this->getUser();
	// flagoubli est à  1 si l'utilisateur se connecte 
	// pour la première fois après avoit reçu un mdp car il faut
	// l'obliger à changer
	$flagoubli = 0;
	// test si l'utilisateur est anonyme
	if(null === $user){
	    $name = 'anonyme';
	    $statut = 'VISITEUR';
	    $id = NULL;
	}
	else{
	    // récupérer l'identifiant et le statut
	    $name = $user->getUsername();
	    $statut = $user->getStatut();
	    $id = $user->getId();
	}

	//récupération du repository liste des stock
	$repository = $em->getRepository('SYM16SimpleStockBundle:Stocklist');

	// récuprération du service session
	$session = $this->get('session');

	// récupération du nom interne du stock par défaut (qui devient le stock courant)
	// codés en dur dans .../Symfony/app/config/config.yml et .../Symfony/app/config/paramters.yml
	$stockname = $this->container->getParameter('currentstockname');

	// récupération du nom d'usage du stock courant
	$stockusage = $repository->findOneByNom($stockname)->getUsage();
	// récupération du nom de connection du stock courant
	$connection = $repository->findOneByUsage($stockusage)->getConnection();
	//reucupération de tous les stocks
	$stocklist = $repository->findAll();
	// recupération du repository locator
	$repository = $em->getRepository('SYM16SimpleStockBundle:Locator');
	$locator  =  $repository->find(1);
	$sitename = $locator->getSite();
	$sendermail = $locator->getSendermail();
	$notificationmail = $locator->getNotificationmail();


	// initiatisation des variables de sessions
	$session->set('stockuser', $name);
	$session->set('stockuserid', $id);
	$session->set('stockuserstatut', $statut);
	$session->set('stocklist', $stocklist);
	$session->set('stockname', $stockname);
	$session->set('stockconnection', $connection);
	$session->set('stockusage', $stockusage);
	$session->set('sitename', $sitename);
	$session->set('sendermail', $sendermail);
	$session->set('notificationmail', $notificationmail);

        // si le flag oubli est à 0 c'est une connexion normale
	if($id != NULL){
	    // récupérer le repository des utilisateurs
	    $repository = $em->getRepository("SYM16UserBundle:User");
	    //obtenir son flagoubli
	    $user = $repository->find($id);
	    $flagoubli = $user->getFlagoubli();
	    $session->set('flagoubli',$flagoubli); 
	}
	if($flagoubli == 0)
	    return $this->render('SYM16SimpleStockBundle:Acceuil:index.html.twig', array('name' => $name));
	//sinon il faut changer le mod de passe
	else
            return $this->redirect($this->generateUrl("sym16_simple_stock_utilisateur_modifiermdp",
               array('valeur' => $id, 'exposant' => $id))
	    );
    }

    /**
    *
    * @Route("/changestock", name="sym16_simple_stock_changestock")
    */ 
    public function changestockAction(Request $request)
    {
	//récupération de l'entity manager pour la base de données stockmaster
	// elle contient notamment la table avec la liste des stocks
	$em = $this->getDoctrine()->getManager('stockmaster');
	$repository = $em->getRepository('SYM16SimpleStockBundle:Stocklist');
	// recupération du nom d'usage du stock
	$stockusage = $request->query->get('stockusage');
	// récupération du nom interne du stock
	$stockname = $repository->findOneByUsage($stockusage)->getNom();
	// récupération du nom de connection
	$connection = $repository->findOneByUsage($stockusage)->getConnection();
	// récuprération du service session
	$session = $this->get('session');
	// assignation à des variables de session
	$session->set('stockusage', $stockusage);
	$session->set('stockname', $stockname);
	$session->set('stockconnection', $connection);

	// pour le return
	$name = $session->get('stockuser');
        return $this->render('SYM16SimpleStockBundle:Acceuil:index.html.twig', array('name' => $name));
    }
}
