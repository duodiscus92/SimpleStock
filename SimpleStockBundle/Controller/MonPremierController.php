<?php
// src/SYM16/SimpleStockBundle/Controller/MonPremierController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use SYM16\SimpleStockBundle\Entity\PetitesFournitures;

/*class MonPremierController extends Controller
{
    public function iLikeAction(){
	$content = $this->get('templating')->render
		('SYM16SimpleStockBundle:MonPremier:iLike.html.twig');
        return new Response($content);
    }
}*/

class MonPremierController extends Controller
{
    //J'aime ....
    public function iLikeAction($un_verbe_a_l_infinitif){
        return new Response("J'aime beaucoup ".$un_verbe_a_l_infinitif.' !');
    }
/*
    public function produitAction($multiplicande, $multiplicateur){
	$produit = hexdec($multiplicande) * hexdec($multiplicateur);
	return new Response
	   ("Le produit de " . $multiplicande . " par ". $multiplicateur. 
		" est égale à : ". $produit); 
    }
*/
    // produit de deux nombres
    public function produitAction($multiplicande, $multiplicateur){
	$produit = hexdec($multiplicande) * hexdec($multiplicateur);
	return $this->render(
		'SYM16SimpleStockBundle:MonPremier:produit.html.twig',
		array('multiplicande' => $multiplicande,
		      'multiplicateur' => $multiplicateur,
		      'resultat' => $produit)
        );
    }

    //elevation a une puissance
    public function puissanceAction(Request $request) {
	$valeur = $request->query->get('valeur');
	$exposant = $request->query->get('exposant');
	$resultat =  pow($valeur, $exposant);
	return $this->render(
		'SYM16SimpleStockBundle:MonPremier:puissance.html.twig',
		array('valeur' => $valeur,
		      'exposant' => $exposant,
		      'resultat' => $resultat)
        );
    }

    // extraction d'une racine (pas encore implementee)
    public function racineAction($valeur, $radical){

	$this->get('session')->getFlashBag()
		->add('inforacine','Choix indisponible, faites un autre choix');
	$this->get('session')->getFlashBag()
		->add('inforacine','Presser F5 pour supprimer ce message flash');

	$url = $this->get('router')->
	    generate('sym16_simple_stock_homepage', array('name' =>'Jacques'));
	return new RedirectResponse($url);

/*	return this->redirectToRoute
	    ('sym16_simple_stock_homepage', array('name' =>'Jacques'));
*/
    }
    //lister un tableau (constuit en dur dans cette fonction, en vérité proviendra d'une BDD)
/*    public function listerAction() {
	// contruction de la première ligne (ligne d'intitulé)
	$listColnames = array('ID', 'Libellé', 'Prix HT', 'Prix TTC');
	// construction des autres lignes
	$listEntities = array( 
		array('id'=>'3', 'Vis',100,120),	// un article : son id, son label, ses prix HT et TTC
		array('id'=>4, 'Ecrou', 50, 60),	// un autre article : idem
		array('id'=>7, 'Rondelle', 10, 12));	// un autre article : idem
        $path=array(
		'mod'=>'sym16_simple_stock_puissance',	// le chemin qui traitera l'action modifier (ici c'est n'importe quoi)
		'supr'=>'sym16_simple_stock_puissance');// le chemin qui traitera l'action supprimer (ici c'est n'importe quoi)

	return $this->render(
		'SYM16SimpleStockBundle:MonPremier:list.html.twig',
		array('listColnames' => $listColnames, 'listEntities'=> $listEntities, 'path'=>$path)
        );
    }*/

    //lister un tableau (données provenenant d'une BDD)
    public function listerAction() {
	// contruction de la première ligne (ligne d'intitulé)
	$listColnames = array('ID', 'Libellé', 'Prix HT', 'Prix TTC');
	//construction des autres lignes du tableay
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère touot le contenu de la table
	$entities = $em->getRepository('SYM16SimpleStockBundle:PetitesFournitures')->findAll();
	if(null == $entities){
		throw new NotFoundHttpException("La liste n'existe pas");
	}
	$listEntities = array();
	//boucle sur les lignes de la table
	foreach ($entities as $entitie){
		// chaque ligne est elle meme un tableau d'articles qu'on obtier avec les getters
		$listArticle = array('id' => $entitie->getId(), $entitie->getLibelle(), $entitie->getPrixht(), $entitie->getTtc());
		// listeEntities est un tableau de tableau crée dynamiquement (on sait pas a priori le nombre de lignes)
		array_push($listEntities, $listArticle);
	}

	// construction des autres lignes
        $path=array(
		'mod'=>'sym16_simple_stock_modifier',	// le chemin qui traitera l'action modifier
		'supr'=>'sym16_simple_stock_supprimer');// le chemin qui traitera l'action supprimer
	
	return $this->render(
		'SYM16SimpleStockBundle:MonPremier:list.html.twig',
		array('listColnames' => $listColnames, 'listEntities'=> $listEntities, 'path'=>$path)
        );
    }

    // ajouter un article dans l'entité
    public function ajouterAction(Request $request){
	// récuparation des valeurs
	$libelle = $request->query->get('libelle');
	$prixht = $request->query->get('prixht');
	$tva = $request->query->get('tva');

	// creation d'une instance de l'entité et  hydratation
	$pf = new PetitesFournitures();
	$pf->setLibelle($libelle);
	$pf->setPrixht($prixht);
	$pf->setTva($tva);

	// récupe de l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on persiste
	$em->persist($pf);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }

    // supprimer un article
    public function supprimerAction(Request $request) {
	// récupe de l'id de l'article à supprimer
        $id = $request->query->get('valeur');
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager();
        //récuparartion de l'entite d'id  $id
        $pf = $em->getRepository("SYM16SimpleStockBundle:PetitesFournitures")->find($id);
	// suppression de l'entité
	$em->remove($pf);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }
}
