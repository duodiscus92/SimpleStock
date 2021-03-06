<?php

namespace SYM16\SimpleStockBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FamilleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FamilleRepository extends EntityRepository
{
	// Nombre total d'entrerpots
	public function getNbItems()
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('count(f)')->from('SYM16SimpleStockBundle:Famille', 'f');
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur	
		return $query->getSingleScalarResult();
	}

	//pour assuurer la compatibilité ascendante
	//cette fonction est obsolète
	public function getNbFamille()
	{
		return $this->getNbItems();
	}

}
