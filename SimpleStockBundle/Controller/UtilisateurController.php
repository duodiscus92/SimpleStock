<?php
// src/SYM16/SimpleStockBundle/Controller/UtilisateurController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use SYM16\SimpleStockBundle\Entity\Utilisateur;
use SYM16\SimpleStockBundle\Entity\Droit;

class UtilisateurController extends Controller
{

    //lister un tableau (données provenenant d'une BDD)
    public function listerAction() {
	// contruction de la première ligne (ligne d'intitulé)
	$listColnames = array('ID', 'Nom', 'Prénom', 'Droit');
	//construction des autres lignes du tableay
	// on récupère l'entity manager
	$em = $this->getDoctrine()->getManager();
	// on récupère touot le contenu de la table
	$entities = $em->getRepository('SYM16SimpleStockBundle:Utilisateur')->findAll();
	if(null == $entities){
		throw new NotFoundHttpException("La liste n'existe pas");
	}
	$listEntities = array();
	//boucle sur les lignes de la table
	foreach ($entities as $entitie){
		// chaque ligne est elle meme un tableau d'articles qu'on obtier avec les getters
		$listArticle = array('id' => $entitie->getId(), $entitie->getNom(), $entitie->getPrenom(), $entitie->getDroit()->getPrivilege());
		// listeEntities est un tableau de tableau crée dynamiquement (on sait pas a priori le nombre de lignes)
		array_push($listEntities, $listArticle);
	}

	// construction des autres lignes
        $path=array(
		'mod'=>'sym16_simple_stock_utilisateur_modifier',	// le chemin qui traitera l'action modifier
		'supr'=>'sym16_simple_stock_utilisateur_supprimer');// le chemin qui traitera l'action supprimer
	
	return $this->render(
		'SYM16SimpleStockBundle:MonPremier:list.html.twig',
		array('listColnames' => $listColnames, 'listEntities'=> $listEntities, 'path'=>$path)
        );
    }

    // ajouter un article dans l'entité
    public function ajouterAction(Request $request){
	// récuparation des valeurs passées par GET
	$nom = $request->query->get('nom');
	$prenom = $request->query->get('prenom');
	$droit = $request->query->get('droit');

	// on récupère l'entité inverse correspondante au droit (récupéré ci-dessus)
	$em = $this->getDoctrine()->getManager();
	$privilege = $em->getRepository('SYM16SimpleStockBundle:Droit')->
		findOneByPrivilege($droit);

	// creation d'une instance de l'entité propriétaire et hydratation
	$user = new Utilisateur();
	$user->setNom($nom);
	$user->setPrenom($prenom);
	$user->setDroit($privilege);

	// on persiste
	$em->persist($user);
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
        $user = $em->getRepository("SYM16SimpleStockBundle:Utilisateur")->find($id);
	// suppression de l'entité
	$em->remove($user);
	$em->flush();
	// affichage de la liste reactualisee
	return $this->listerAction();
    }
}
