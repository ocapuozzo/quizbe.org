<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType {

  private $classroom;

  public function __construct($classroom) {
    $this->classroom = $classroom;
  }

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $scopes = $this->classroom->getScopes();
    
    $builder
        // ->add('datecrea') // valorisation automatique
        ->add('name')
        
        ->add('classroom', 'entity', array(
            'class' => 'AppBundle:Classroom',
            'property' => 'name',
            'data' => $this->classroom,
            'choices' => array($this->classroom),//$this->user->getClassrooms(),
        ))

//           ->add('designer', 'hidden')    * unexposed *
//           ->add('avgRating', 'hidden')
        ->add('codesigners')
        ->add('scope', 'entity', array(
            'class' => 'AppBundle:Scope', 
            'property' => 'name',
            'choices' => $scopes   //array($this->classroom->getScopes())
            ))
        ->add('sentence', 'textarea',  array(
            'attr' => array(
            'class' => 'tinymce' )
        ))
        ->add('responses', 'collection', array(
            'type' => new ResponseType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
         ))
         ->add('doPublish', 'checkbox', array(
            'mapped' => false,
            'required' => false 
         ));   
  }

  /**
   * @param OptionsResolverInterface $resolver
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\Question',
        'cascade_validation' => true
    ));
  }

  /**
   * @return string
   */
  public function getName() {
    return 'appbundle_question';
  }

}
