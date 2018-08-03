<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormEmail
 *
 * @author clever
 */

namespace Clever\CleverSMSFormBundle\WebServiceContactProxy\Model;

class FormEmail {

    //put your code here
    private $from;
    private $message;
    private $replyTo;
    private $subject;
    private $to;
    private $personal;
    public function getPersonal() {
        return $this->personal;
    }

    public function setPersonal($personal) {
        $this->personal = $personal;
    }

        

    public function getFrom() {
        return $this->from;
    }

    public function setFrom($from) {
        $this->from = $from;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getReplyTo() {
        return $this->replyTo;
    }

    public function setReplyTo($replyTo) {
        $this->replyTo = $replyTo;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getTo() {
        return $this->to;
    }

    public function setTo($to) {
        $this->to = $to;
    }

}

?>
