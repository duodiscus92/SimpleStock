<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class ReferenceFiltre
{
    const FILTRE_TOUS = 't';
    const FILTRE_UNIQUEMENT = 'u';
    const FILTRE_SAUF = 's';

    public function __construct()
    {
	$this->entrepotfiltre =
	$this->famillefiltre =
	$this->createurfiltre =
	$this->refvide = self::FILTRE_TOUS;
    }


    private $id;
    private $refvide;
    private $entrepot;
    private $entrepotfiltre;
    private $famille;
    private $famillefiltre;
    private $createur;
    private $createurfiltre;

    public function getId()
    {
        return $this->id;
    }

    public function setRefvide($refvide)
    {
        $this->refvide = $refvide;

        return $this;
    }

    public function getRefvide()
    {
        return $this->refvide;
    }

    public function setEntrepot($entrepot)
    {
        $this->entrepot = $entrepot;

        return $this;
    }

    public function getEntrepot()
    {
        return $this->entrepot;
    }

    public function setEntrepotfiltre($entrepotfiltre)
    {
        $this->entrepotfiltre = $entrepotfiltre;

        return $this;
    }

    public function getEntrepotfiltre()
    {
        return $this->entrepotfiltre;
    }

    public function setFamille($famille)
    {
        $this->famille = $famille;

        return $this;
    }

    public function getFamille()
    {
        return $this->famille;
    }

    public function setFamillefiltre($famillefiltre)
    {
        $this->famillefiltre = $famillefiltre;

        return $this;
    }

    public function getFamillefiltre()
    {
        return $this->famillefiltre;
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
}
