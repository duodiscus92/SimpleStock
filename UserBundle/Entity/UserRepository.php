<?php

namespace SYM16\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	// même que précédent mais avec Query Builder
	public function getNbItems()
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('count(u)')->from('SYM16UserBundle:User', 'u');
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur	
		return $query->getSingleScalarResult();
	}

}
