<?php

namespace SYM16\SimpleStockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Droit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SYM16\SimpleStockBundle\Entity\Repository\DroitRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"privilege"},
 * message="Ce privilege a déjà été utilisé",
 * )
 */
class Droit extends EntityTools
{
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
     * @ORM\Column(name="privilege", type="string", length=255, unique=true)
     */
    private $privilege;


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
     * Set privilege
     *
     * @param string $privilege
     * @return Droit
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = strtoupper($this->wd_remove_accents($privilege));

        return $this;
    }

    /**
     * Get privilege
     *
     * @return string 
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }
}
