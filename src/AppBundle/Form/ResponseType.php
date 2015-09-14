<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\AppConstants;

class ResponseType extends AbstractType
{
  public static $VALUES = 
      array("-2"=>-2, "-1.5"=>"-1.5", "-1"=>"-1"
      ,"0.5"=>".5", "1"=>"1", "1.5"=>"1.5", "2" => "2");
  
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {          
        $builder
            ->add('proposition', 'textarea',  array(
            'attr' => array(
            'class' => 'tinymce' )
             ))  // Rem : redefine in views/Response/prototype.html.twig
            ->add('feedback')
            ->add('value', 'choice', array(
                'choices' => self::$VALUES,
                ))
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
