<?php
namespace  SYM16\SimpleStockBundle\Services;
use Symfony\Component\HttpFoundation\Response;

// regroupe tous les services de listage
class Lister
{
    // convertit tout type (ou presque) en chaine
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

    // prestation : liste toute l'entité  passée en paramètre (pas de filtre)
    public function listerEntite($liste) {

	// nom des entêtes de colonne
	$listColnames  = $liste['listcolnames'];
	// valeurs des entité à lister
	$entities = $liste['entities'];
	//
	//
	// à compléter
	//
	//
	// lister !
	return array(
		// vue à utiliser pour lister
		'listtwig' => 'SYM16SimpleStockBundle:MonPremier:list.html.twig',
		// ce qu'il faut lister
		'tab' => array('listColnames' => $listColnames, 'listEntities' => $listEntities, 'path' => $path, 'totaluser' => $totaluser)
	);
    }
}

