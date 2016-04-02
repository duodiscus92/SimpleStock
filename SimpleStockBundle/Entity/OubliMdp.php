<?php
namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// cette classe permet la saisie par formulaire
// de l'identidiant et du mail en cas de mdp perdu ou oublié
// pas d'annotation ORM car les données ne sont jamais persistées
class OubliMdp
{
    private $username;
    private $email;
    private $createur;

    public function getUsername()
    {
    	return $this->username;
    }

    public function setUsername($username)
    {
    	$this->username = $username;
	return $this;
    }

    public function getEmail()
    {
    	return $this->email;
    }

    public function setEmail($email)
    {
    	$this->email = $email;
	return $this;
    }

    public function setCreateur($createur)
    {
	$this->createur = $createur;
	return $this;
    }
}

