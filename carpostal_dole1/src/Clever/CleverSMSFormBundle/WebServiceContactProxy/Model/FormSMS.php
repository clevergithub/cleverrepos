<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormSMS
 *
 * @author clever
 */

namespace Clever\CleverSMSFormBundle\WebServiceContactProxy\Model;

class FormSMS {

    private $message;
    private $numGSM;

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getNumGSM() {
        return $this->numGSM;
    }

    public function setNumGSM($numGSM) {
        $this->numGSM = $numGSM;
    }

}

?>
