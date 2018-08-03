<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContactType
 *
 * @author clever
 */

namespace Clever\CleverSMSFormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Clever\CleverSMSFormBundle\Form\DataContactType;

class ContactType extends AbstractType {

    //put your code here

     private $fields;
    
    public function __construct($fields) { 
        $this->fields=$fields;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('name', 'text', array('error_bubbling' => true))
                ->add('lastname', 'text', array('error_bubbling' => true))
                ->add('phone', 'text', array('error_bubbling' => true))
                ->add('datacontact', new DataContactType($this->fields));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Clever\CleverSMSFormBundle\Model\Contact'
        ));
    }

    public function getName() {
        return 'contact';
    }

}

?>
