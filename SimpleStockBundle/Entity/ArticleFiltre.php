<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class ArticleFiltre
{
    const FILTRE_TOUS = 't';
    const FILTRE_UNIQUEMENT = 'u';
    const FILTRE_SAUF = 's';

    public function __construct()
    {
	$this->recherchefiltre =
	$this->referencefiltre =
	$this->nomfiltre =
	$this->createurfiltre =
	$this->commentairefiltre =
	$this->refvide = self::FILTRE_TOUS;
    }


    private $id;
    private $recherche;
    private $recherchefiltre;
    private $reference;
    private $referencefiltre;
    private $nom;
    private $nomfiltre;
    private $createur;
    private $createurfiltre;
    private $commentaire;
    private $commentairefiltre;

    public function getId()
    {
        return $this->id;
    }

    public function setRecherche($recherche)
    {
        $this->recherche = $recherche;

        return $this;
    }

    public function getRecherche()
    {
        return $this->recherche;
    }

    public function setRecherchefiltre($recherchefiltre)
    {
        $this->recherchefiltre = $recherchefiltre;

        return $this;
    }

    public function getRecherchefiltre()
    {
        return $this->recherchefiltre;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReferencefiltre($referencefiltre)
    {
        $this->referencefiltre = $referencefiltre;

        return $this;
    }

    public function getReferencefiltre()
    {
        return $this->referencefiltre;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNomfiltre($nomfiltre)
    {
        $this->nomfiltre = $nomfiltre;

        return $this;
    }

    public function getNomfiltre()
    {
        return $this->nomfiltre;
    }

    public function setCreateur($createur)
    {
        $this->createur = $createur;

        return $this;
    }

    public function getCreateur()
    {
        return $this->createur;
    }

    public function setCreateurfiltre($createurfiltre)
    {
        $this->createurfiltre = $createurfiltre;

        return $this;
    }

    public function getCreateurfiltre()
    {
        return $this->createurfiltre;
    }

    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function setCommentairefiltre($commentairefiltre)
    {
        $this->commentairefiltre = $commentairefiltre;

        return $this;
    }

    public function getCommentairefiltre()
    {
        return $this->commentairefiltre;
    }

}
