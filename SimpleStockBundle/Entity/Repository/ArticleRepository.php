<?php

namespace SYM16\SimpleStockBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
	// Nombre total d'articles
	public function getNbItems()
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('count(r)')->from('SYM16SimpleStockBundle:Article', 'r');
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur	
		return $query->getSingleScalarResult();
	}

	//pour assuurer la compatibilité ascendante
	//cette fonction est obsolète
	public function getNbArticle()
	{
		return $this->getNbItems();
	}

	//delivre la quantité totale d'article de référence donnée disponible
	public function getQteTotale($reference)
	{
		//on crée le query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('sum(a.quantite)')->from('SYM16SimpleStockBundle:Article', 'a');
		$querybuilder->innerJoin('a.reference', 'r');
		$querybuilder->where('r.ref = :reference')->setParameter('reference', $reference);
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		return $query->getSingleScalarResult();

	}

	//delivre la quantité totale d'article de référence donnée disponible
	public function findByRef($reference)
	{
		//on crée le query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('a')->from('SYM16SimpleStockBundle:Article', 'a');
		$querybuilder->innerJoin('a.reference', 'r');
		$querybuilder->where('r.ref = :reference')->setParameter('reference', $reference);
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		return $query->getResult();

	}

}
