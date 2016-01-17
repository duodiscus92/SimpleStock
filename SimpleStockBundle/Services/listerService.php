<?php
namespace  SYM16\SimpleStockBundle\Services;
use Symfony\Component\HttpFoundation\Response;

class listerService
{
    // liste une entité
    private function AnyTypeToString($var)
    {
	//echo (gettype($var).'<br>');
	if(is_integer($var))
	    return (string)$var;
	else if(is_bool($var))
	    return ($var == 0 ? 'false' : 'true');
	else if(is_object($var))
	    return $var->format('Y-m-d H:i:s');
	else if(is_null($var))
	    return 'null';
	else
	    return $var;
    }

    public function listerEntite($liste) {

	// nom des entêtes de colonne
	$listColnames  = $liste['listcolnames'];
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
		foreach ($listColnames as $key => $fieldname){
		    $fieldname = 'get'.$fieldname;
		    // traitement special de l'id
		    if ($key == 'id')
			$listArticle['id'] = $this->AnyTypeToString($entitie->$fieldname());
		    else
		    	array_push($listArticle, $this->AnyTypeToString($entitie->$fieldname()));
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

