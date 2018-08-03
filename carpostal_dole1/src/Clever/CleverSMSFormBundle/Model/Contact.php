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

namespace Clever\CleverSMSFormBundle\Model;

use \Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Contact implements UserInterface {

    /**
     *
     * @var integer L'identifiant du contact
     * @access private
     */
    private $idContact;

    /**
     *
     * 
     * @var string Le numéro de téléphone mobile
     * @access private
     * @Assert\Regex(
     *     pattern="/^0[1-9][0-9]{8}$/",
     *     match=true,
     *     message="Le numéro de tél. mobile est incorrect."
     * )
    
     */
    private $phone;

    /**
     *
     * @var string Le prénom du contact
     * @access private
     * @Assert\Length(
     * min="2",
     * max="50",
     * minMessage="Le prénom doit faire au moins 2 caractères.",
     * maxMessage="Le prénom doit faire au plus 50 caractères."
     * )
     * @Assert\NotBlank(
     * message="Veuillez renseigner votre prénom."
     * )
     */
    private $lastName;

    /**
     *
     * @var string Le nom du contact
     * @access private
     * @Assert\Length(
     * min="2",
     * max="50",
     * minMessage="Le nom doit faire au moins 2 caractères.",
     * maxMessage="Le nom doit faire au plus 50 caractères."
     * )
     * @Assert\NotBlank(
     * message="Veuillez renseigner votre nom."
     * )
     */
    private $name;
    /**
     *
     * @var \Clever\CleverSMSFormBundle\Model\DataContact dataContact
     * @Assert\Valid() 
     */
    private $dataContact;
    
    /**
     *
     * @var string  Identifiant du contact
     */
    private $username;
    /**
     *
     * @var string Password Mot de passe du contact 
     */
    private $password; // string
    
    /**
     *
     * @var array Roles du contact
     */
    private $roles=array('a:1:{i:0;s:9:"ROLE_CONTACT";}'); // string
    private $salt='';

    /**
     *
     * @var datetime Date d'enregistrement du contact
     */
    private $dateRegistration;
    
     /**
     *
     * @var datetime Date de modification du contact
     */
    private $dateModification;
    
   
    public function getDateModification() {
        return $this->dateModification;
    }
    public function getDateRegistration() {
        return $this->dateRegistration;
    }

    public function setDateRegistration( $dateRegistration) {
        $this->dateRegistration = $dateRegistration;
    }

    
    public function setDateModification( $dateModification) {
        $this->dateModification = $dateModification;
    }

        public function getDataContact() {
        return $this->dataContact;
    }

    /**
     * 
     * @param \Clever\CleverSMSFormBundle\Model\DataContact $dataContact
     */
    public function setDataContact(\Clever\CleverSMSFormBundle\Model\DataContact $dataContact) {
        $this->dataContact = $dataContact;
    }

    public function getIdContact() {
        return $this->idContact;
    }

    public function setIdContact($idContact) {
        $this->idContact = $idContact;
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

    /**
     * 
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    
    public function eraseCredentials() {
        
    }

     /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles() {
        return $this->roles;
    }


}

?>
