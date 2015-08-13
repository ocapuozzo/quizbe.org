<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QuestionRepository")
 */
class Question
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
     * @var \DateTime
     *
     * @ORM\Column(name="datecrea", type="datetime")
     */
    private $datecrea;

    /**
     * @var string
     *
     * @ORM\Column(name="designers", type="string", length=255)
     */
    private $designers;
    
    /**
     * @var string (portée de la question : dev objet, dev web, dev initiation...)
     * @ORM\ManyToOne(targetEntity="Scope")
     * @ORM\JoinColumn(name="idScope", referencedColumnName="id")
     */
    private $scope;
    

    /**
     * @ORM\OneToMany(targetEntity="Response", mappedBy="question")
     */
    protected $responses;
    
    
     public function __construct()
    {
        $this->datecrea = new \DateTime();
        $this->responses = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Question
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
     * Set datecrea
     *
     * @param \DateTime $datecrea
     * @return Question
     */
    public function setDatecrea($datecrea)
    {
        $this->datecrea = $datecrea;

        return $this;
    }

    /**
     * Get datecrea
     *
     * @return \DateTime 
     */
    public function getDatecrea()
    {
        return $this->datecrea;
    }

    /**
     * Set designers
     *
     * @param string $designers
     * @return Question
     */
    public function setDesigners($designers)
    {
        $this->designers = $designers;

        return $this;
    }

    /**
     * Get designers
     *
     * @return string 
     */
    public function getDesigners()
    {
        return $this->designers;
    }

    /**
     * Add reponses
     *
     * @param \AppBundle\Entity\Response $responses
     * @return Question
     */
    public function addResponse(\AppBundle\Entity\Response $responses)
    {
        $this->responses[] = $responses;

        return $this;
    }

    /**
     * Remove reponses
     *
     * @param \AppBundle\Entity\Response $responses
     */
    public function removeResponse(\AppBundle\Entity\Response $responses)
    {
        $this->responses->removeElement($responses);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponses()
    {
        return $this->responses;
    }



    /**
     * Set scope
     *
     * @param \AppBundle\Entity\Scope $scope
     * @return Question
     */
    public function setScope(\AppBundle\Entity\Scope $scope = null)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return \AppBundle\Entity\Scope 
     */
    public function getScope()
    {
        return $this->scope;
    }
}
