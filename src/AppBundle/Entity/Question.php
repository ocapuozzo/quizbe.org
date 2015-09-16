<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QuestionRepository")
 * @UniqueEntity(fields="name", message="Question name already exists")
 */
class Question {

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
   * @ORM\Column(name="name", type="string", length=50)
   */
  private $name;

  /**
   * @var string
   *
   * @ORM\Column(name="sentence", type="text")
   */
  private $sentence;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="datecrea", type="datetime")
   */
  private $datecrea;


  /**
   * 
   * @var string username of designer author (or ManyToOne User ?)
   *
   * @ORM\Column(name="designer", type="string", length=50)
   */
  private $designer;

  
  
  /**
   * @var string
   *
   * @ORM\Column(name="codesigners", type="string", length=255, nullable=true)
   */
  private $codesigners;

  /**
   * portée de la question : dev objet, dev web, dev initiation...
   * @ORM\ManyToOne(targetEntity="Scope")
   * @ORM\JoinColumn(name="idScope", referencedColumnName="id")
   */
  private $scope;

  
  /**
   * 
   * @ORM\ManyToOne(targetEntity="Classroom")
   * @ORM\JoinColumn(name="idClassroom", referencedColumnName="id")
   */
  private $classroom;
  
  
  /**
   * @ORM\OneToMany(targetEntity="Response", cascade={"persist", "remove"}, mappedBy="question")
   */
  protected $responses;


  /**
   * @ORM\OneToMany(targetEntity="Rating", cascade={"remove"}, mappedBy="question")
   */
  protected $ratings;

  
    /**
   * @var \DateTime
   *
   * @ORM\Column(name="datepub", type="datetime", nullable=true)
   */
  private $datepub;

  
  public function __construct() {
    $this->datecrea = new \DateTime();
    $this->responses = new ArrayCollection();
  }

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set name
   *
   * @param string $name
   * @return Question
   */
  public function setName($name) {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string 
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get sentence
   *
   * @return string 
   */
  public function getSentence() {
    return $this->sentence;
  }

  /**
   * Set sentence
   *
   * @param string $sentence
   * @return Question
   */
  public function setSentence($sentence) {
    $this->sentence = $sentence;

    return $this;
  }

  /**
   * Set datecrea
   *
   * @param \DateTime $datecrea
   * @return Question
   */
  public function setDatecrea($datecrea) {
    $this->datecrea = $datecrea;

    return $this;
  }

  /**
   * Get datecrea
   *
   * @return \DateTime 
   */
  public function getDatecrea() {
    return $this->datecrea;
  }

  /**
   * Set designer
   *
   * @param string $designer
   * @return Question
   */
  public function setDesigner($designer) {
    $this->designer = $designer;

    return $this;
  }

  /**
   * Get codesigners
   *
   * @return string 
   */
  public function getDesigners() {
    return $this->codesigners;
  }

  /**
   * Add response
   *
   * @param \AppBundle\Entity\Response $response
   * @return Question
   */
  public function addResponse(\AppBundle\Entity\Response $response) {
    $this->responses[] = $response;
    $response->setQuestion($this);
    return $this;
  }

  /**
   * Remove response
   *
   * @param \AppBundle\Entity\Response $response
   */
  public function removeResponse(\AppBundle\Entity\Response $response) {
    $this->responses->removeElement($response);
  }

  /**
   * Get responses
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getResponses() {
    return $this->responses;
  }

  /**
   * Set responses
   *
   * @param \AppBundle\Entity\Response $responses
   */
  public function setResponses(\Doctrine\Common\Collections\Collection $responses) {
    foreach (responses as $resp) {
      $resp->addQuestion(this);
    }
    $this->responses = $responses;
  }

  /**
   * Set scope
   *
   * @param \AppBundle\Entity\Scope $scope
   * @return Question
   */
  public function setScope(\AppBundle\Entity\Scope $scope = null) {
    $this->scope = $scope;

    return $this;
  }

  /**
   * Get scope
   *
   * @return \AppBundle\Entity\Scope 
   */
  public function getScope() {
    return $this->scope;
  }

  /**
   * Get sum values of expected choices
   * @return float
   */  
  public function getExpectedChoices() {
    $res = 0.0;
    
    foreach ($this->getResponses() as $response) {
      if ($response->getValue() > 0) {
        $res += $response->getValue();
      }
    }
    return $res;
  }
 
  
  /**
   * Get number of good choices
   * @return int
   */  
  public function getNbExpectedChoices() {
    $res = 0;
    
    foreach ($this->getResponses() as $response) {
      if ($response->getValue() > 0) {
        $res++;
      }
    }
    return $res;
  }

    /**
     * Set codesigners
     *
     * @param string $codesigners
     * @return Question
     */
    public function setCodesigners($codesigners)
    {
        $this->codesigners = $codesigners;

        return $this;
    }

    /**
     * Get designer
     *
     * @return string 
     */
    public function getDesigner()
    {
        return $this->designer;
    }

  
    /**
     * Get codesigners
     *
     * @return string 
     */
    public function getCodesigners()
    {
        return $this->codesigners;
    }

    /**
     * Is user designer of this question ?
     * 
     * @param User $user
     * @return boolean
     */
    public function isDesigner($user) {
      if ($user) {
        return $this->getDesigner() == $user->getUsername();
      }
      return false;
    }
    
    
    /**
     * Is user co-designer of this question ?
     * 
     * @param User $user
     * @return boolean
     */
    public function isCoDesigner($user) {
      if ($user) {
        $codesigners = preg_split("/[\s,]+/", $this->getCodesigners());
        return  in_array($user->getUsername(),$codesigners);
      }          
      return false;
    }
    
    /**
     * Only designer and co-designer can update 
     * 
     * @param User $user
     * @return boolean
     */
    public function isCanUpdate($user) {     
      return $this->isDesigner($user) || $this->isCoDesigner($user);
    }
        

    /**
     * Add ratings
     *
     * @param \AppBundle\Entity\Rating $rating
     * @return Question
     */
    public function addRating(\AppBundle\Entity\Rating $rating)
    {
        $this->ratings[] = $rating;

        return $this;
    }

    /**
     * Remove rating
     *
     * @param \AppBundle\Entity\Rating $rating
     */
    public function removeRating(\AppBundle\Entity\Rating $rating)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }
    
    /**
     * Get nb ratings
     *
     * @return integer
     */
    public function getCountRatings()
    {
        return $this->ratings->count();
    }
    
    
    /**
     * Get AVG ratings
     *
     * @return float
     */
    public function getAvgRatings()
    {
       if (!$this->ratings || !$this->ratings->count())
         return 0;
     
       $res = 0.0;
      
       foreach ($this->ratings as $r) {
         $res += $r->getValue(); 
       }
       return $res / $this->ratings->count();
    }

    public function getAuthors() {
      if ($this->codesigners)
        return $this->designer . ', ' . $this->codesigners;
      
      return $this->designer;
    }

    /**
     * Set classroom
     *
     * @param \AppBundle\Entity\Classroom $classroom
     * @return Question
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

    /**
     * Set datepub
     *
     * @param \DateTime $datepub
     * @return Question
     */
    public function setDatepub($datepub)
    {
        $this->datepub = $datepub;

        return $this;
    }

    /**
     * Get datepub
     *
     * @return \DateTime 
     */
    public function getDatepub()
    {
        return $this->datepub;
    }
    
    /**
     * Get text Representation
     *
     * @return text Representation
     */
    public function toText()
    {
       $title = $this->name .'(' . $this->designer . ' ' . $this->codesigners .')';
       $txtQuestion = $this->sentence;
       $txtResponses = "\n";
       foreach ($this->getResponses() as $r) {
          $txtResponses .= '[ ] ' . $r->getProposition() . "\n";  
       }
       return $title . "\n" . $txtQuestion . "\n" . $txtResponses . "\n" ;
    }
}
