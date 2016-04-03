<?php
// src/SYM16/SimpleStockBundle/Controller/MailController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\UserBundle\Entity\User;
use SYM16\UserBundle\Entity\Locator;

/**
 *
 * Classe Mail
 *
 * @Route("/mail")
 */
class MailController extends Controller
{
    private $sendermail;
    private $notificationmail;
    private $sitename;
    /**
     * envoyer un mail de confirmation à un nouvel inscrit
     *
     * @Route("/confreg/{id}", name="sym16_simple_stock_mail_confreg")
     */
    public function confregMailAction($id)
    {
	// récuprération du service session
	$session = $this->get('session');
	// récupération des variables de session
	// mail de l'emmetteur
	$this->sendermail = $session->get('sendermail');
	// mail de notification
	$this->notificationmail = $session->get('notificationmail');
	// nom du site
	$this->sitename = $session->get('sitename');

	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
        //récupérartion de l'entite d'id  $id
        $entity = $em->getRepository("SYM16UserBundle:User")->find($id);
	// message au nouvel inscrit
        $message = \Swift_Message::newInstance()
           ->setSubject("[SimpleStock-".$this->sitename."] Confirmation d'inscription")
           ->setFrom($this->sendermail)
           ->setTo($entity->getEmail())
           ->setBody(
                 $this->renderView(
                    'SYM16SimpleStockBundle:Mails:registration.html.twig',
                     array('site' => $this->sitename, 'nom' => $entity->getNom(), 'prenom' => $entity->getPrenom())
                )
           )
        ;
        $this->get('mailer')->send($message);

        // notification au gestionnaire du stock
	$message = \Swift_Message::newInstance()
           ->setSubject("[SimpleStock-".$this->sitename."] Un nouvel utilisateur s'est inscrit")
           ->setFrom($this->sendermail)
           ->setTo($this->notificationmail)
           ->setBody(
                 $this->renderView(
                    'SYM16SimpleStockBundle:Mails:notifregistration.html.twig',
                     array('site' => $this->sitename,  'nom' => $entity->getNom(), 'prenom' => $entity->getPrenom())
                )
           )
        ;
        $this->get('mailer')->send($message);
        return $this->render('SYM16SimpleStockBundle:Common:inforegistrationdone.html.twig',
            array('statut' => 'TEMPORAIRE', 'homepath' => "sym16_simple_stock_homepage"));
     }

    /**
     * envoyer un mail pour toute action ajouter, modifier, supprimer
     *
     * @Route("/ams/{item}/{nature}/{objet}/{createur}/{route}", name="sym16_simple_stock_mail_ams")
     */
    public function amsAction($item, $nature, $objet, $createur, $route)
    {
	// récuprération du service session
	$session = $this->get('session');
	// récupération des variables de session
	// mail de l'emmetteur
	$this->sendermail = $session->get('sendermail');
	// mail de notification
	$this->notificationmail = $session->get('notificationmail');
	// nom du site
	$this->sitename = $session->get('sitename');
	// nom d'usage du stock
	$stockusage = $session->get('stockusage');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
        //récupérartion de l'entite de l'utilisateur courant
        //$entity = $em->getRepository("SYM16UserBundle:User")->findOneByUsername($createur);
	// récupération de tous les utilisateurs
	$users = $em->getRepository("SYM16UserBundle:User")->findAll();
	//boucle sur les utilisateurs
	foreach ($users as $user) {
	// message aux utilisateurs
	    //on vérifie s'ils accetent d'être notitfiés
	    if($user->getAcp() == 1){
        	$message = \Swift_Message::newInstance()
           	    ->setSubject("[SimpleStock-".$this->sitename."] Changement de paramètres")
           	    ->setFrom($this->sendermail)
           	    ->setTo($user->getEmail())
           	    ->setBody(
                 	$this->renderView(
                    	    'SYM16SimpleStockBundle:Mails:ams.html.twig',
                     	    array('site' => $this->sitename, 'nom' => $user->getNom(), 'prenom' => $user->getPrenom(),
			    	'item' => $item, 'stock' => $stockusage, 'nature' => $nature, 'objet' => $objet, 'createur' => $session->get('stockuser'))
                	)
           	    )
        	;
        	$this->get('mailer')->send($message);
	    }
	}
        return $this->redirect($this->generateUrl($route));
    }

    /**
     * envoyer un mail pour toute action dépot/retrait 
     *
     * @Route("/adr/{item}/{nature}/{objet}/{createur}/{route}", name="sym16_simple_stock_mail_adr")
     */
    public function adrAction($item, $nature, $objet, $createur, $route)
    {
	// récuprération du service session
	$session = $this->get('session');
	// récupération des variables de session
	// mail de l'emmetteur
	$this->sendermail = $session->get('sendermail');
	// mail de notification
	$this->notificationmail = $session->get('notificationmail');
	// nom du site
	$this->sitename = $session->get('sitename');
	// nom d'usage du stock
	$stockusage = $session->get('stockusage');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
        //récupérartion de l'entite de l'utilisateur courant
        //$entity = $em->getRepository("SYM16UserBundle:User")->findOneByUsername($createur);
	// récupération de tous les utilisateurs
	$users = $em->getRepository("SYM16UserBundle:User")->findAll();
	//boucle sur les utilisateurs
	foreach ($users as $user) {
	// message aux utilisateurs
	    //on vérifie s'ils accetent d'être notitfiés
	    if($user->getAdr() == 1){
        	$message = \Swift_Message::newInstance()
           	    ->setSubject("[SimpleStock-".$this->sitename."] Dépot ou retrait d'articles")
           	    ->setFrom($this->sendermail)
           	    ->setTo($user->getEmail())
           	    ->setBody(
                 	$this->renderView(
                    	    'SYM16SimpleStockBundle:Mails:adr.html.twig',
                     	    array('site' => $this->sitename, 'nom' => $user->getNom(), 'prenom' => $user->getPrenom(),
			    	'item' => $item, 'stock' => $stockusage, 'nature' => $nature, 'objet' => $objet, 'createur' => $session->get('stockuser'))
                	)
           	    )
        	;
        	$this->get('mailer')->send($message);
	    }
	}
        return $this->redirect($this->generateUrl($route));
    }

    /**
     * envoyer un mail en cas d'alerte stock inférieur ou égal à seuil 
     *
     * @Route("/asb/{reference}/{reliquat}/{seuil}/{createur}/{route}", name="sym16_simple_stock_mail_asb")
     */
    public function asbAction($reference, $reliquat, $seuil, $createur, $route)
    {
	// récuprération du service session
	$session = $this->get('session');
	// récupération des variables de session
	// mail de l'emmetteur
	$this->sendermail = $session->get('sendermail');
	// mail de notification
	$this->notificationmail = $session->get('notificationmail');
	// nom du site
	$this->sitename = $session->get('sitename');
	// nom d'usage du stock
	$stockusage = $session->get('stockusage');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
	// récupération de tous les utilisateurs
	$users = $em->getRepository("SYM16UserBundle:User")->findAll();
	//boucle sur les utilisateurs
	foreach ($users as $user) {
	// message aux utilisateurs
	    //on vérifie s'ils accetent d'être notitfiés
	    if($user->getAsb() == 1){
        	$message = \Swift_Message::newInstance()
           	    ->setSubject("[SimpleStock-".$this->sitename."] Alerte seuil bas !!!!")
           	    ->setFrom($this->sendermail)
           	    ->setTo($user->getEmail())
           	    ->setBody(
                 	$this->renderView(
                    	    'SYM16SimpleStockBundle:Mails:asb.html.twig',
                     	    array('site' => $this->sitename,
				  'nom' => $user->getNom(),
				  'prenom' => $user->getPrenom(),
			    	  'stock' => $stockusage,
				  'seuil' => $seuil,
				  'reliquat' => $reliquat,
				  'reference' => $reference,
				  'createur' => $session->get('stockuser'))
                	)
           	    )
        	;
        	$this->get('mailer')->send($message);
	    }
	}
        return $this->redirect($this->generateUrl($route));
    }

    /**
     * envoyer un mail de confirmation à un nouvel inscrit
     *
     * @Route("mdpenvoi/{id}", name="sym16_simple_stock_mail_mdpenvoi")
     */
    public function mdpenvoiMailAction($id)
    {
	// récuprération du service session
	$session = $this->get('session');
	// récupération des variables de session
	// mail de l'emmetteur
	$this->sendermail = $session->get('sendermail');
	// nom du site
	$this->sitename = $session->get('sitename');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
        //récupérartion de l'entite d'id  $id
        $entity = $em->getRepository("SYM16UserBundle:User")->find($id);
	// génération du nouveau mdp
	//$nouveaumdp = 'hellohello';
	$nouveaumdp = uniqid();;
	//hydratation
        $entity->setPassword($nouveaumdp);
        $entity->setModification(new \Datetime());
	//enregistrement dans la bdd
	$em->persist($entity);
	$em->flush();
	// message au nouvel inscrit
        $message = \Swift_Message::newInstance()
           ->setSubject("[SimpleStock-".$this->sitename."] Nouveau mot de passe")
           ->setFrom($this->sendermail)
           ->setTo($entity->getEmail())
           ->setBody(
                 $this->renderView(
                    'SYM16SimpleStockBundle:Mails:mdpenvoi.html.twig',
                     array('site' => $this->sitename, 'nom' => $entity->getNom(), 'prenom' => $entity->getPrenom(), 'nouveaumdp' => $nouveaumdp)
                )
           )
        ;
        $this->get('mailer')->send($message);
        return $this->render('SYM16SimpleStockBundle:Common:infooublimdpdone.html.twig',
            array('statut' => 'TEMPORAIRE', 'homepath' => "sym16_simple_stock_homepage"));
     }
}
