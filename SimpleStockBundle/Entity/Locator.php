<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Locator
 *
 * @ORM\Table(name="Locator", uniqueConstraints={@ORM\UniqueConstraint(name="client", columns={"client"}), @ORM\UniqueConstraint(name="site", columns={"site"})})
 * @ORM\Entity
 */
class Locator extends EntityTools 
{
    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255, nullable=false)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=255, nullable=false)
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="rue1", type="string", length=255, nullable=false)
     */
    private $rue1;

    /**
     * @var string
     *
     * @ORM\Column(name="rue2", type="string", length=255, nullable=false)
     */
    private $rue2;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=false)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="codepotal", type="string", length=255, nullable=false)
     */
    private $codepotal;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=false)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="contactprenom", type="string", length=255, nullable=false)
     */
    private $contactprenom;

    /**
     * @var string
     *
     * @ORM\Column(name="contactnom", type="string", length=255, nullable=false)
     */
    private $contactnom;

    /**
     * @var string
     *
     * @ORM\Column(name="contactmail", type="string", length=255, nullable=false)
     */
    private $contactmail;

    /**
     * @var string
     *
     * @ORM\Column(name="contacttel", type="string", length=255, nullable=false)
     */
    private $contacttel;

    /**
     * @var string
     *
     * @ORM\Column(name="sendermail", type="string", length=255, nullable=false)
     */
    private $sendermail;

    /**
     * @var string
     *
     * @ORM\Column(name="notificationmail", type="string", length=255, nullable=false)
     */
    private $notificationmail;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Set site
     *
     * @param string $site
     * @return Locator
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set client
     *
     * @param string $client
     * @return Locator
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return string 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set rue1
     *
     * @param string $rue1
     * @return Locator
     */
    public function setRue1($rue1)
    {
        $this->rue1 = $rue1;

        return $this;
    }

    /**
     * Get rue1
     *
     * @return string 
     */
    public function getRue1()
    {
        return $this->rue1;
    }

    /**
     * Set rue2
     *
     * @param string $rue2
     * @return Locator
     */
    public function setRue2($rue2)
    {
        $this->rue2 = $rue2;

        return $this;
    }

    /**
     * Get rue2
     *
     * @return string 
     */
    public function getRue2()
    {
        return $this->rue2;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Locator
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set codepotal
     *
     * @param string $codepotal
     * @return Locator
     */
    public function setCodepotal($codepotal)
    {
        $this->codepotal = $codepotal;

        return $this;
    }

    /**
     * Get codepotal
     *
     * @return string 
     */
    public function getCodepotal()
    {
        return $this->codepotal;
    }

    /**
     * Set pays
     *
     * @param string $pays
     * @return Locator
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set contactprenom
     *
     * @param string $contactprenom
     * @return Locator
     */
    public function setContactprenom($contactprenom)
    {
        $this->contactprenom = $contactprenom;

        return $this;
    }

    /**
     * Get contactprenom
     *
     * @return string 
     */
    public function getContactprenom()
    {
        return $this->contactprenom;
    }

    /**
     * Set contactnom
     *
     * @param string $contactnom
     * @return Locator
     */
    public function setContactnom($contactnom)
    {
        $this->contactnom = $contactnom;

        return $this;
    }

    /**
     * Get contactnom
     *
     * @return string 
     */
    public function getContactnom()
    {
        return $this->contactnom;
    }

    /**
     * Set contactmail
     *
     * @param string $contactmail
     * @return Locator
     */
    public function setContactmail($contactmail)
    {
        $this->contactmail = $contactmail;

        return $this;
    }

    /**
     * Get contactmail
     *
     * @return string 
     */
    public function getContactmail()
    {
        return $this->contactmail;
    }

    /**
     * Set contacttel
     *
     * @param string $contacttel
     * @return Locator
     */
    public function setContacttel($contacttel)
    {
        $this->contacttel = $contacttel;

        return $this;
    }

    /**
     * Get contacttel
     *
     * @return string 
     */
    public function getContacttel()
    {
        return $this->contacttel;
    }

    /**
     * Set sendermail
     *
     * @param string $sendermail
     * @return Locator
     */
    public function setSendermail($sendermail)
    {
        $this->sendermail = $sendermail;

        return $this;
    }

    /**
     * Get sendermail
     *
     * @return string 
     */
    public function getSendermail()
    {
        return $this->sendermail;
    }

    /**
     * Set notificationmail
     *
     * @param string $notificationmail
     * @return Locator
     */
    public function setNotificationmail($notificationmail)
    {
        $this->notificationmail = $notificationmail;

        return $this;
    }

    /**
     * Get notificationmail
     *
     * @return string 
     */
    public function getNotificationmail()
    {
        return $this->notificationmail;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
