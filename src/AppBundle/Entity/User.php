<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="degree", type="string", length=25, nullable=true)
     * 
     */
    private $degree;

    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="isteacher", type="boolean", options={"default":false})
     * 
     */
    private $isteacher;

    
    public function __construct()
    {
      parent::__construct();

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

    /**
     * Set degree
     *
     * @param string $degree
     * @return User
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return string 
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set isteacher
     *
     * @param boolean $isteacher
     * @return User
     */
    public function setIsteacher($isteacher)
    {
        $this->isteacher = $isteacher;

        return $this;
    }

    /**
     * Get isteacher
     *
     * @return boolean 
     */
    public function getIsteacher()
    {
        return $this->isteacher;
    }
}
