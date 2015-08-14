<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QuestionRepository")
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
   * @ORM\Column(name="sentence", type="string", length=255)
   */
  private $sentence;

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
   * @ORM\OneToMany(targetEntity="Response", cascade={"persist"}, mappedBy="question")
   */
  protected $responses;

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
   * Set designers
   *
   * @param string $designers
   * @return Question
   */
  public function setDesigners($designers) {
    $this->designers = $designers;

    return $this;
  }

  /**
   * Get designers
   *
   * @return string 
   */
  public function getDesigners() {
    return $this->designers;
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

  public function getExpectedChoices() {
    $res = 0.0;
    
    foreach ($this->getResponses() as $response) {
      if ($response->getValue() > 0) {
        $res += $response->getValue();
      }
    }
    return $res;
  }

}
