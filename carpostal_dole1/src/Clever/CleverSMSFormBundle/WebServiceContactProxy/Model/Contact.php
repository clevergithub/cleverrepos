<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contact
 *
 * @author clever
 */

namespace Clever\CleverSMSFormBundle\WebServiceContactProxy\Model;

class Contact {

    private $idContact;
    
    private $countryCode;/*la classe countrycode n'est exploitée que pendant la phase d'authentification pour restituer les données de la page modification*/
    private $phone;
    private $lastName;
    private $name;
    private $additionalFields;

    /* private $username; // string
      private $password; // string
      private $roles; // string
      private $salt; // string */

//    public function setUsername($username) {
//        $this->username = $username;
//    }
//
//    public function setPassword($password) {
//        $this->password = $password;
//    }
//
//    public function setRoles($roles) {
//        $this->roles = $roles;
//    }
//
//    public function setSalt($salt) {
//        $this->salt = $salt;
//    }

    public function getIdContact() {
        return $this->idContact;
    }

    public function setIdContact($idContact) {
        $this->idContact = $idContact;
    }

    public function getCountryCode() {
        return $this->countryCode;
    }

    public function setCountryCode(\Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\CountryCode $countryCode) {
        $this->countryCode = $countryCode;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getAdditionalFields() {
        return $this->additionalFields;
    }

    public function addAdditionalField(\Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\AdditionalField $additionalField) {
        $this->additionalFields[] = $additionalField;
    }
/*
    public function eraseCredentials() {
        
    }

    public function getPassword() {

        foreach ($this->additionalFields as $additional) {
            if ($additional->fieldName == 'eti80') {
                return $additional->value;
            }
        }
        return null;
    }

    public function getRoles() {
        return array('a:1:{i:0;s:9:"ROLE_CONTACT";}');
    }

    public function getSalt() {
        return '';
    }

    public function getUsername() {

        foreach ($this->additionalFields as $additional) {
            if ($additional->fieldName == 'eti79') {
                return $additional->value;
            }
        }
        return null;
    }

    /* public function serialize() {

      }

      public function unserialize($serialized) {

      } */
}

?>
