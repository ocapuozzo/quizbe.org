<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Classroom
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ClassroomRepository")
 * @UniqueEntity(fields="name", message="Class already exists")
 */
class Classroom
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
     * @ORM\Column(name="name", type="string", length=80)
     */
    private $name;

    
    /**
     * @var User ("owner" can edit)
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    protected $user;    
 

    /**
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Scope", cascade={"persist", "remove"}, mappedBy="classroom")
     */
    protected $scopes;    
   
    
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
     * @return Classroom
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Classroom
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scopes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add scope
     *
     * @param \AppBundle\Entity\Scope $scope
     * @return Classroom
     */
    public function addScope(\AppBundle\Entity\Scope $scope)
    {
        $this->scopes[] = $scope;
        $scope->setClassroom($this);
        return $this;
    }

    /**
     * Remove scope
     *
     * @param \AppBundle\Entity\Scope $scope
     */
    public function removeScope(\AppBundle\Entity\Scope $scope)
    {
        $this->scopes->removeElement($scope);
    }

    /**
     * Get scopes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getScopes()
    {
        return $this->scopes;
    }
    
    /**
     * http://symfony.com/fr/doc/current/reference/constraints/Callback.html
     * 
     * @Assert\Callback(message = "error.noscope")
     */
    public function validate(ExecutionContextInterface $context)
    {
       
       if (!$this->getScopes()->count()) {
        $context->addViolationAt(
            'scopes',
            'error.noscope',  // TODO : ??? translators domain don't work ???
            array('messages'),
            null
        );
    }
    }
    
}
