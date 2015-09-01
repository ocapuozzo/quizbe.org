<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\AppConstants;

class ResponseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $valuesList = array();
          
          for ($i = AppConstants::MIN_VALUE_PROPOSITION;
               $i<= AppConstants::MAX_VALUE_PROPOSITION;
               $i = $i + AppConstants::STEP_VALUE_PROPOSITION)
          {                      
            $valuesList[sprintf("%.1f", $i)]=$i;
          }
          
        $builder
            ->add('proposition')
            ->add('feedback')
            ->add('value', 'choice', array(
                'choices' => $valuesList))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Response'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_response';
    }
}
