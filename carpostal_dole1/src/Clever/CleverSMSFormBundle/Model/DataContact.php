<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataContact
 *
 * @author clever
 */

namespace Clever\CleverSMSFormBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class DataContact {

    /**
     *
     * @var string civilité du contact
     * @access private
     * @Assert\Choice(choices = {"M.", "Mme","Mlle"}, message = "La civilité n'est pas correct.")
     * 
     */
    private $gender;

    /**
     *
     * @var string adresse email du contact
     * @access private
     * @Assert\Email(
     *     message = "L'adresse email n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     *
     * 
     * @var string Le numéro de téléphone fixe
     * @access private
     * @Assert\Regex(
     *     pattern="/^0[1-9][0-9]{8}$/",
     *     match=true,
     *     message="Le numéro de tél. mobile est incorrect."
     * )
    
     */
    private $landlinePhone;

   /**
     *
     * @var string La ville du contact
     * @access private
     * @Assert\Length(
     * min="2",
     * max="50",
     * minMessage="Le nom de la ville que vous avez renseigné est trop court.",
     * maxMessage="Le nom de la ville que vous avez renseigné est trop long."
     * )
     */
    private $city;

     /**
     *
     * @var string L'adresse du contact
     * @access private
     * @Assert\Length(
     * min="2",
     * max="50",
     * minMessage="L'adresse que vous avez renseignée est trop court.",
     * maxMessage="L'adresse que vous avez renseignée est trop long."
     * )
     */
    private $address;

    /**
     *
     * @var Numéro d'abonné
     * @Assert\Type(type="numeric", message="Le numéro d'abonné est incorrect.")
     * @Assert\Length(
     * max="50",
     * maxMessage="Le numéro d'abonné que vous avez renseigné est trop long.")
     */
    private $subscriberNumber;

    /**
     *
     * @var string Âge
     */
    private $old;

    /**
     *
     * @var string Les lignes de bus 
     */
    private $bus;

    /**
     *
     * @var string La fréquence d'utilisation
     */
    private $frequencyOfUse;
    
    /**
     *
     * @var string titre d'utilisation
     */
    
    private $titleOfTransport;

    /**
     *
     * @var integer Choix pour la désinscription
     */
    private $unregister;

    /**
     *
     * @var Captcha 
     */
    private $captcha;

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getOld() {
        return $this->old;
    }

    public function setOld($old) {
        $this->old = $old;
    }

    public function getTitleOfTransport() {
        return $this->titleOfTransport;
    }

    public function setTitleOfTransport($titleOfTransport) {
        $this->titleOfTransport = $titleOfTransport;
    }

        
    public function getGender() {
        return $this->gender;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getLandlinePhone() {
        return $this->landlinePhone;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setLandlinePhone($landlinePhone) {
        $this->landlinePhone = $landlinePhone;
    }

    public function getCity() {
        return $this->city;
    }

 

    public function getSubscriberNumber() {
        return $this->subscriberNumber;
    }

   

    public function setCity($city) {
        $this->city = $city;
    }


    public function setSubscriberNumber($subscriberNumber) {
        $this->subscriberNumber = $subscriberNumber;
    }

 

    public function getBus() {
        return $this->bus;
    }

    public function getFrequencyOfUse() {
        return $this->frequencyOfUse;
    }

    public function getUnregister() {
        return $this->unregister;
    }

    public function getCaptcha() {
        return $this->captcha;
    }

    public function setBus($bus) {
        $this->bus = $bus;
    }

    public function setFrequencyOfUse($frequencyOfUse) {
        $this->frequencyOfUse = $frequencyOfUse;
    }

    public function setUnregister($unregister) {
        $this->unregister = $unregister;
    }

    public function setCaptcha(Captcha $captcha) {
        $this->captcha = $captcha;
    }

}
