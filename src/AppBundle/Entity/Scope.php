<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Scope
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Scope
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /** 
     * @var Classroom ("owner" can edit)
     * @ORM\ManyToOne(targetEntity="Classroom", inversedBy="scopes")           
     * @ORM\JoinColumn(name="id_classroom", referencedColumnName="id")
     */
    private $classroom;
    
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
     * Set name
     *
     * @param string $name
     * @return Scope
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set classroom
     *
     * @param \AppBundle\Entity\Classroom $classroom
     * @return Scope
     */
    public function setClassroom(\AppBundle\Entity\Classroom $classroom = null)
    {
        $this->classroom = $classroom;

        return $this;
    }

    /**
     * Get classroom
     *
     * @return \AppBundle\Entity\Classroom 
     */
    public function getClassroom()
    {
        return $this->classroom;
    }
}
