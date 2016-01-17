<?php
namespace  SYM16\SimpleStockBundle\Services;
use Symfony\Component\HttpFoundation\Response;

class listerService
{
    // liste une entité

    public function listerEntite($liste) {

	// nom des entêtes de colonne
	$listColnames = $liste['colnames'];
	// valeurs des entité à lister
	$entities = $liste['entities'];
	if(null == $entities){
		throw new NotFoundHttpException("La liste n'existe pas");
	}
	$listEntities = array();
	//boucle sur les lignes de la table
	foreach ($entities as $entitie){
		// chaque ligne est elle meme un tableau d'articles qu'on obtient avec les getters
		$listArticle = array();
		$fcts = $liste['fcts'];
		foreach ($fcts as $key => $fct){
		    // traitement special de l'id
		    if ($key == 'id')
			$listArticle['id'] = $entitie->$fct();
		    // traitement special de la clé étrangère
		    else if ($key == 'ce'){
			$fct = current($fcts['ce']);
			$subfct = next($fcts['ce']);
		    	array_push($listArticle, $entitie->$fct()->$subfct());
		    }
		    else
		    	array_push($listArticle, $entitie->$fct());
		}
		// listeEntities est un tableau de tableau crée dynamiquement (on sait pas a priori le nombre de lignes)
		array_push($listEntities, $listArticle);
		unset($listArticle);
	}
	// construction des autres lignes
	$path = $liste['path'];
	// construction	 d'informations globales aux entités (ex : nombre de lignes de la liste, total TTC
	$totaluser = $liste['totalusers'];
	// lister !
	return array(
		'listtwig' => 'SYM16SimpleStockBundle:MonPremier:list.html.twig',
		'tab' => array('listColnames' => $listColnames, 'listEntities' => $listEntities, 'path' => $path, 'totaluser' => $totaluser)
	);
    }
}

