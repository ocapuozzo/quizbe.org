<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Classroom", cascade={"persist"})
     */
    protected $classrooms;    
    
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
      $this->classrooms = new ArrayCollection();
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

    /**
     * Add classroom
     *
     * @param \AppBundle\Entity\Classroom $classroom
     * @return User
     */
    public function addClassroom(\AppBundle\Entity\Classroom $classroom)
    {
        $this->classrooms[] = $classroom;

        return $this;
    }

    /**
     * Remove classroom
     *
     * @param \AppBundle\Entity\Classroom $classroom
     */
    public function removeClassroom(\AppBundle\Entity\Classroom $classroom)
    {
        $this->classrooms->removeElement($classroom);
    }

    /**
     * Get classrooms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClassrooms()
    {
        return $this->classrooms;
    }
}
