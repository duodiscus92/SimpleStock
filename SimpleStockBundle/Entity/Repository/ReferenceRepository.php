<?php

namespace SYM16\SimpleStockBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ReferenceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReferenceRepository extends EntityRepository
{
	// Nombre total de références
	public function getNbItems()
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('count(r)')->from('SYM16SimpleStockBundle:Reference', 'r');
		// on récuère la query à partir du quesrybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur	
		return $query->getSingleScalarResult();
	}
	
	//pour assuurer la compatibilité ascendante
	//cette fonction est obsolète
	public function getNbReference()
	{
		return $this->getNbItems();
	}
	// filter selon une liste de critères
	public function findByFilter($filtre)
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('r')->from('SYM16SimpleStockBundle:Reference', 'r');
		$querybuilder->leftJoin('r.entrepot', 'e');
		$querybuilder->leftjoin('r.famille', 'f');
		// traitement du uniquement
		if( ($ef = $filtre['u']['entrepot']) == NULL)
		    $querybuilder->where('e.nom IS NOT NULL');
		else 
		    $querybuilder->where('e.nom = :inclureEntrepot')->setParameter('inclureEntrepot', $ef);

		if( ($ef = $filtre['u']['famille']) == NULL)
		    $querybuilder->andWhere('f.nom IS NOT NULL');
		else 
		    $querybuilder->andWhere('f.nom = :inclureFamille')->setParameter('inclureFamille', $ef);

		if( ($ef = $filtre['u']['createur']) == NULL)
		    $querybuilder->andWhere('r.createur IS NOT NULL');
		else
		    $querybuilder->andWhere('r.createur = :inclureCreateur')->setParameter('inclureCreateur', $ef);

		//traitement du sauf
		if( ($ef = $filtre['s']['entrepot']) == NULL)
		    $querybuilder->andWhere('e.nom IS NOT NULL');
		else 
		    $querybuilder->andWhere('e.nom <> :inclureEntrepot')->setParameter('inclureEntrepot', $ef);

		if( ($ef = $filtre['s']['famille']) == NULL)
		    $querybuilder->andWhere('f.nom IS NOT NULL');
		else 
		    $querybuilder->andWhere('f.nom <> :inclureFamille')->setParameter('inclureFamille', $ef);

		if( ($ef = $filtre['s']['createur']) == NULL)
		    $querybuilder->andWhere('r.createur IS NOT NULL');
		else
		    $querybuilder->andWhere('r.createur <> :inclureCreateur')->setParameter('inclureCreateur', $ef);

		// on récupère la query à partir du querybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur
		return $query->getResult();
	}

	// compter le nb d'article selon un filtre
	public function countByFilter($filtre)
	{
		// on crée un query builder
		$querybuilder = $this->_em->CreateQueryBuilder();
		$querybuilder->select('COUNT(r)')->from('SYM16SimpleStockBundle:Reference', 'r');
		$querybuilder->leftJoin('r.entrepot', 'e');
		$querybuilder->leftjoin('r.famille', 'f');
		// traitement du uniquement
		if( ($ef = $filtre['u']['entrepot']) == NULL)
		    $querybuilder->where('e.nom IS NOT NULL');
		else 
		    $querybuilder->where('e.nom = :inclureEntrepot')->setParameter('inclureEntrepot', $ef);

		if( ($ef = $filtre['u']['famille']) == NULL)
		    $querybuilder->andWhere('f.nom IS NOT NULL');
		else 
		    $querybuilder->andWhere('f.nom = :inclureFamille')->setParameter('inclureFamille', $ef);

		if( ($ef = $filtre['u']['createur']) == NULL)
		    $querybuilder->andWhere('r.createur IS NOT NULL');
		else
		    $querybuilder->andWhere('r.createur = :inclureCreateur')->setParameter('inclureCreateur', $ef);

		//traitement du sauf
		if( ($ef = $filtre['s']['entrepot']) == NULL)
		    $querybuilder->andWhere('e.nom IS NOT NULL');
		else 
		    $querybuilder->andWhere('e.nom <> :inclureEntrepot')->setParameter('inclureEntrepot', $ef);

		if( ($ef = $filtre['s']['famille']) == NULL)
		    $querybuilder->andWhere('f.nom IS NOT NULL');
		else 
		    $querybuilder->andWhere('f.nom <> :inclureFamille')->setParameter('inclureFamille', $ef);

		if( ($ef = $filtre['s']['createur']) == NULL)
		    $querybuilder->andWhere('r.createur IS NOT NULL');
		else
		    $querybuilder->andWhere('r.createur <> :inclureCreateur')->setParameter('inclureCreateur', $ef);

		// on récupère la query à partir du querybuilder
		$query = $querybuilder->getQuery();
		// on l'exécute et on renvoie sa valeur
		return $query->getSingleScalarResult();
	}
}
