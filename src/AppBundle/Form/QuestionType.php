<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('datecrea') // valorisation automatique
           ->add('name')
//           ->add('designer', 'hidden')
           ->add('codesigners')
           ->add('scope', 'entity', 
              array('class'=> 'AppBundle:Scope', 'property' => 'name' ))
   
            ->add('sentence', 'textarea')
            ->add('responses', 'collection', array(
                'type' => new ResponseType(),
                 'allow_add' => true,
                 'allow_delete' => true,
                 'by_reference' => false,
                ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Question',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_question';
    }
}
