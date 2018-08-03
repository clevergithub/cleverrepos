<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of countryCode
 *
 * @author clever
 */

namespace Clever\CleverSMSFormBundle\WebServiceContactProxy\Model;

class CountryCode {

    private $idCode;
    private $code;

    public function getIdCode() {
        return $this->idCode;
    }

    public function setIdCode($idCode) {
        $this->idCode = $idCode;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

}

?>
