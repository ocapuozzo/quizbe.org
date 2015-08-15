<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
   private $translator;
   
   public function __construct($translator) {  
     $this->translator = $translator;
   }
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('degree');
        $builder->add('isteacher'
            ,'checkbox'
            , array(
               'label' => $this->translator
                     ->trans('isteacher', array(),'messages', null),
               'required'  => false));
     }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}
