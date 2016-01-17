<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\UtilisateurRepository")
 */
class Utilisateur
{
    /**
    * @ORM\ManyToOne(targetEntity="SYM16\SimpleStockBundle\Entity\Droit")
    * @ORM\JoinColumn(nullable=false)
    */
    private $droit;

    public function getDroit()
    {
	return $this->droit;
    }

    public function setDroit(Droit $droit)
    {
	$this->droit = $droit;
	return $this;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=255)
     */
    private $mdp;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="asb", type="boolean")
     */
    private $asb;

    /**
     * @var boolean
     *
     * @ORM\Column(name="adp", type="boolean")
     */
    private $adp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="art", type="boolean")
     */
    private $art;

    /**
     * @var boolean
     *
     * @ORM\Column(name="acr", type="boolean")
     */
    private $acr;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Utilisateurs
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Utilisateurs
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Utilisateurs
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     * @return Utilisateurs
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string 
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Utilisateurs
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set asb
     *
     * @param boolean $asb
     * @return Utilisateurs
     */
    public function setAsb($asb)
    {
        $this->asb = $asb;

        return $this;
    }

    /**
     * Get asb
     *
     * @return boolean 
     */
    public function getAsb()
    {
        return $this->asb;
    }

    /**
     * Set adp
     *
     * @param boolean $adp
     * @return Utilisateurs
     */
    public function setAdp($adp)
    {
        $this->adp = $adp;

        return $this;
    }

    /**
     * Get adp
     *
     * @return boolean 
     */
    public function getAdp()
    {
        return $this->adp;
    }

    /**
     * Set art
     *
     * @param boolean $art
     * @return Utilisateurs
     */
    public function setArt($art)
    {
        $this->art = $art;

        return $this;
    }

    /**
     * Get art
     *
     * @return boolean 
     */
    public function getArt()
    {
        return $this->art;
    }

    /**
     * Set acr
     *
     * @param boolean $acr
     * @return Utilisateurs
     */
    public function setAcr($acr)
    {
        $this->acr = $acr;

        return $this;
    }

    /**
     * Get acr
     *
     * @return boolean 
     */
    public function getAcr()
    {
        return $this->acr;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Utilisateurs
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
