<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Rating : when a user valuate a question
 *
 * @ORM\Table(name="rating", 
  uniqueConstraints={@UniqueConstraint(name="only_one_rating", columns={"id_user", "id_question"})}),
  indexes={@Index(name="question_idx", columns={"id_question"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RatingRepository")
 * @UniqueEntity(fields={"user", "question"}, message="rating.userquestion")
 */
class Rating {

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * question
   * @ORM\ManyToOne(targetEntity="Question")
   * @ORM\JoinColumn(name="id_question", referencedColumnName="id")
   */
  private $question;

  /**
   * user
   * @ORM\ManyToOne(targetEntity="User")
   * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
   */
  private $user;

  /**
   * @var int
   *
   * @ORM\Column(name="value", type="decimal", scale=2)
   */
  private $value;

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
   * @return Scope
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
   * Set value
   *
   * @param integer $value
   * @return Rating
   */
  public function setValue($value) {
    $this->value = $value;

    return $this;
  }

  /**
   * Get value
   *
   * @return integer 
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Set question
   *
   * @param \AppBundle\Entity\Question $question
   * @return Rating
   */
  public function setQuestion(\AppBundle\Entity\Question $question = null) {
    $this->question = $question;

    return $this;
  }

  /**
   * Get question
   *
   * @return \AppBundle\Entity\Question 
   */
  public function getQuestion() {
    return $this->question;
  }

  /**
   * Set user
   *
   * @param \AppBundle\Entity\User $user
   * @return Rating
   */
  public function setUser(\AppBundle\Entity\User $user = null) {
    $this->user = $user;

    return $this;
  }

  /**
   * Get user
   *
   * @return \AppBundle\Entity\User 
   */
  public function getUser() {
    return $this->user;
  }

}
