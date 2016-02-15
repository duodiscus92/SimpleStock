<?php

namespace SYM16\SimpleStockBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ComposantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComposantRepository extends EntityRepository
{
	// même que précédent mais avec Query Builder
	public function getNbItems()
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('count(c)')->from('SYM16SimpleStockBundle:Composant', 'c');
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur	
		return $query->getSingleScalarResult();
	}

	// même que précédent mais avec Query Builder
	public function getNbComposantById($id)
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('count(c)')->from('SYM16SimpleStockBundle:Composant', 'c');
		$querybuilder->where('c.famille = :id')->setParameter('id', $id);
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur	
		return $query->getSingleScalarResult();
	}

	// même que précédent mais avec Query Builder
	public function getNbComposantByFamille($famille)
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('c')->from('SYM16SimpleStockBundle:Composant', 'c');
		$querybuilder->leftJoin('c.famille', 'f');
		$querybuilder->where('f.nom = :statut')->setParameter('nom', $famille);
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur	
		return $query->getResult();
	}

	//pour assuurer la compatibilité ascendante
	//cette fonction est obsolète
	public function getNbComposant()
	{
		return $this->getNbItems();
	}

}
