<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataContactType
 *
 * @author clever
 */

namespace Clever\CleverSMSFormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DataContactType extends AbstractType {

    /**
     * tableau des champs additionnels
     */
    private $fields;

    /**
     * Nous sortons tous les champs de types choices pour les manipuler dans le constructeur plus facilement
     * Rien ne nous oblige à le faire, on aurait pu directement initialiser leurs valeurs dans la fonction buildfForm 
     */
    private $gender;
    private $frequencyOfUse;
    private $old;
    private $titleOfTransport;

    /**/

    public function __construct($fields) {

        $this->fields = $fields;
        $this->gender = array('M.' => 'M.', 'Mme' => 'Mme', 'Mlle' => 'Mlle');
        $this->frequencyOfUse = array("une fois par jour" => "Une fois par jour", "une fois par semaine" => "Une fois par semaine", "une fois par mois" => "Une fois par mois", "moins de 1  fois par mois" => "Moins de 1  fois par mois");
        $this->titleOfTransport = array("Abonnement annuel" => "Abonnement annuel", "Abonnement annuel réduit" => "Abonnement annuel réduit", "Abonnement mensuel" => "Abonnement mensuel", "Abonnement mensuel réduit" => "Abonnement mensuel réduit", "Carte de 10 voyages" => "Carte de 10 voyages", "Ticket unité" => "Ticket unité");
        $this->old = array("- 18 ans" => "- 18 ans", "18-25 ans" => "18-25 ans", "26-34 ans" => "26-34 ans", "35-50 ans" => "35-50 ans", "+ 50 ans" => "+ 50 ans");

        $this->bus = array($this->fields['bus']['services-scolaires'] => "Services scolaires",
            $this->fields['bus']['ligne1'] => "Ligne 1",
            $this->fields['bus']['ligne2'] => "Ligne 2",
            $this->fields['bus']['ligne3'] => "Ligne 3",
            $this->fields['bus']['ligne10'] => "Ligne 10",
            $this->fields['bus']['ligne11'] => "Ligne 11",
            $this->fields['bus']['ligne12'] => "Ligne 12",
            $this->fields['bus']['ligne13'] => "Ligne 13",
            $this->fields['bus']['ligne14'] => "Ligne 14",
            $this->fields['bus']['ligne15'] => "Ligne 15",
            $this->fields['bus']['ligne16'] => "Ligne 16",
            $this->fields['bus']['ligne17'] => "Ligne 17",
            $this->fields['bus']['ligne18'] => "Ligne 18",
            $this->fields['bus']['ligne19'] => "Ligne 19",
            $this->fields['bus']['ligne20'] => "Ligne 20",
            $this->fields['bus']['ligne21'] => "Ligne 21",
            $this->fields['bus']['ligne22'] => "Ligne 22",
            $this->fields['bus']['ligne23'] => "Ligne 23",
            $this->fields['bus']['ligne-coeur-ville'] => "Lignes coeur de ville",
            $this->fields['bus']['flexi-job'] => "Flexi-job",
            $this->fields['bus']['flexi-prm'] => "Flexi-PMR");
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('gender', 'choice', array('error_bubbling' => true, 'choices' => $this->gender))
                ->add('email', 'email', array('error_bubbling' => true))
                ->add('landlinePhone', 'text', array('required' => false, 'error_bubbling' => true))
                ->add('city', 'text', array("required" => false, 'error_bubbling' => true))
                ->add('address', 'text', array("required" => false, 'error_bubbling' => true))
                ->add('subscriberNumber', 'text', array("required" => false, 'error_bubbling' => true))
                ->add('old', 'choice', array('error_bubbling' => true, "empty_value" => "Sélectionner", 'choices' => $this->old))
                ->add('frequencyOfUse', 'choice', array("required" => false, 'error_bubbling' => true, "empty_value" => "Sélectionner", 'choices' => $this->frequencyOfUse))
                ->add('titleOfTransport', 'choice', array("required" => false, 'error_bubbling' => true, "empty_value" => "Sélectionner", 'choices' => $this->titleOfTransport))
                ->add('bus', 'choice', array('required' => false, 'multiple' => true, 'error_bubbling' => true, 'choices' => $this->bus))
                ->add('captcha', 'captcha', array('error_bubbling' => true, 'invalid_message' => 'Le code du captcha soumis précédemment est incorrect.'))
                ->add('unregister', 'checkbox', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Clever\CleverSMSFormBundle\Model\DataContact'
        ));
    }

    public function getName() {
        return 'datacontact';
    }

}

?>
