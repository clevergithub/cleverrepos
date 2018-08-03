<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdditionalField
 *
 * @author clever
 */
namespace Clever\CleverSMSFormBundle\WebServiceContactProxy\Model;

class AdditionalField {

    //put your code here
    public $fieldName;
    public $value;

    public function getFieldName() {
        return $this->fieldName;
    }

    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

}

?>
