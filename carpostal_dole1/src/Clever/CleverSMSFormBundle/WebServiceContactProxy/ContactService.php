<?php

namespace Clever\CleverSMSFormBundle\WebServiceContactProxy;

use Clever\CleverSMSFormBundle\WebServiceContactProxy\AbstractService;
use Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\Contact as ContactWS;
use Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\FormSMS;
use Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\FormEmail;
use Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\AdditionalField;
use Clever\CleverSMSFormBundle\Model\DataContact;

class ContactService extends AbstractService {

    //les variables statiques
    private static $key = 'SLP';
    private $formGeneralInfos;
    private $fields;
    private static $dateFormat = 'd/m/Y';

    function __construct($wsconnexion, $formGeneralInfos, $fields) {
        parent::__construct();
        $this->formGeneralInfos = $formGeneralInfos;
        $this->fields = $fields;
        $this->setWsdl($wsconnexion['wsdl']);
        $this->setAuthentication($wsconnexion['login'], $wsconnexion['password']);
    }

    /**
     * Fonction qui recherche un contact à partir de son login.
     * @param string $login
     * @return Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\Contact Contact
     */
    public function findARegistrantByLogin($login) {
        return $this->findRegistrantByLogin($login, $this->fields['login']);
    }

    /**
     * Fonction qui recherche un contact à partir de son ID.
     * @param integer $idContact
     * @return Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\Contact Contact
     */
    public function findAContact($idContact) {
        return $this->findContact($idContact);
    }

    /**
     * Fonction qui crée un contact dans le cleversms.
     * @param \Clever\CleverSMSFormBundle\Model\Contact $contact
     * @return integer L'identifiant du contact
     */
    public function createAContact(\Clever\CleverSMSFormBundle\Model\Contact $contact) {
        $randLogin = $contact->getName() . rand(10, 99);
        $contact->setUsername($randLogin);
        $contact->setPassword($randLogin . mt_rand(10, 99));
        $formalContact = $this->buildFormalContact($contact);
        $formalSMS = $this->buildSMS($formalContact,$contact->getUsername(),$contact->getPassword());
        $formalEmail = $this->buildEmail($contact, $this->getEmailRegistrationContent($contact),$this->formGeneralInfos['name']);
        
       
        return $this->createContact($formalContact, $formalEmail, $formalSMS);
    }

    /**
     *  Fonction qui met à jour un contact.
     * @param \Clever\CleverSMSFormBundle\Model\Contact $contact
     */
    public function updateAContact(\Clever\CleverSMSFormBundle\Model\Contact $contact) {

        $formalContact = $this->buildFormalContact($contact);
        $this->updateContact($formalContact);
    }

    /**
     * Cette fonction permet de supprimer un contact à partir de son identifiant.
     * @param integer $idContact Identifiant du Contact
     */
    public function removeAContact($idContact) {
        $this->removeContact($idContact);
    }

    /**
     *
     * @param \Clever\CleverSMSFormBundle\Model\Contact $contact
     * @return boolean
     */
    public function sendPassword(\Clever\CleverSMSFormBundle\Model\Contact $contact) {
        $formalEmail = $this->buildEmail($contact, $this->getEmailPasswordLostContent($contact),$this->formGeneralInfos['nameForPass']);

        if ($formalEmail != null) {
            $this->sendPasswordLost($formalEmail);
            return true;
        }
        return false;
    }

    /**
     * Fonction qui construit une instance de Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\Contact à partir de \Clever\CleverSMSFormBundle\Model\Contact
     * @param \Clever\CleverSMSFormBundle\Model\Contact $contact 
     * @return Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\Contact
     */
    public function buildFormalContact(\Clever\CleverSMSFormBundle\Model\Contact $contact) {

        $contactWs = new ContactWS();
        $contactWs->setName($contact->getName());
        $contactWs->setLastName($contact->getLastName());
        $gsm = ($contact->getPhone() == null || $contact->getPhone() == '') ? $this->formGeneralInfos['defaultCountrycode'] . '600000000' : $this->putNumberToInternational($contact->getPhone());
        $contactWs->setPhone($gsm);
        $contactWs->setIdContact($contact->getIdContact());

        //champ email
        $email = new AdditionalField();
        $email->setFieldName($this->fields['email']);
        $email->setValue($contact->getDataContact()->getEmail());
        $contactWs->addAdditionalField($email);
        //champ date de naissance
        $age = new AdditionalField();
        $age->setFieldName($this->fields['old']);
        $age->setValue($contact->getDataContact()->getOld());
        $contactWs->addAdditionalField($age);
        //champ ville
        $city = new AdditionalField();
        $city->setFieldName($this->fields['city']);
        $city->setValue($contact->getDataContact()->getCity());
        $contactWs->addAdditionalField($city);

        //champ adresse
        $address = new AdditionalField();
        $address->setFieldName($this->fields['address']);
        $address->setValue($contact->getDataContact()->getAddress());
        $contactWs->addAdditionalField($address);

        //champ numero d'abonné
        $subscriberNumber = new AdditionalField();
        $subscriberNumber->setFieldName($this->fields['subscriberNumber']);
        $subscriberNumber->setValue($contact->getDataContact()->getSubscriberNumber());
        $contactWs->addAdditionalField($subscriberNumber);
        
        //champ titre de transport
        $titleOfTransport = new AdditionalField();
        $titleOfTransport->setFieldName($this->fields['titleOfTransport']);
        $titleOfTransport->setValue($contact->getDataContact()->getTitleOfTransport());
        $contactWs->addAdditionalField($titleOfTransport);

         //champ tel fixe
        $landlinePhone = new AdditionalField();
        $landlinePhone->setFieldName($this->fields['landlinePhone']);
        $landlinePhone->setValue($contact->getDataContact()->getLandlinePhone());
        $contactWs->addAdditionalField($landlinePhone);
        
        //fréquenced'utilisation
        $frequencyOfUse = new AdditionalField();
        $frequencyOfUse->setFieldName($this->fields['frequencyOfUse']);
        $frequencyOfUse->setValue($contact->getDataContact()->getFrequencyOfUse());
        $contactWs->addAdditionalField($frequencyOfUse);

        //Civilite
        $gender = new AdditionalField();
        $gender->setFieldName($this->fields['civilite']);
        $gender->setValue($contact->getDataContact()->getGender());
        $contactWs->addAdditionalField($gender);

        //Gestion des bus
        $choosenBus = $contact->getDataContact()->getBus();
        foreach ($this->fields['bus'] as $value) {
            $additionalField = new AdditionalField();
            $additionalField->setFieldName($value);
            if (in_array($value, $choosenBus)) {
                $additionalField->setValue('on');
            } else {
                $additionalField->setValue('');
            }
            $contactWs->addAdditionalField($additionalField);
        }
        //Date Creation et modification
        $dateRegistration = new AdditionalField();
        $dateRegistration->setFieldName($this->fields['datecreation']);
        $dateRegistration->setValue($contact->getDateRegistration());
        $contactWs->addAdditionalField($dateRegistration);
        $dateModification = new AdditionalField();
        $dateModification->setFieldName($this->fields['datemodification']);
        $dateModification->setValue($contact->getDateModification());
        $contactWs->addAdditionalField($dateModification);
        //Login et mot de passe 
        $login = new AdditionalField();
        $login->setFieldName($this->fields['login']);
        $login->setValue($contact->getUsername());
        $contactWs->addAdditionalField($login);
        $password = new AdditionalField();
        $password->setFieldName($this->fields['password']);
        $password->setValue($contact->getPassword());
        $contactWs->addAdditionalField($password);

        return $contactWs;
    }

    public function buildContact($registrant) {

        $contact = new \Clever\CleverSMSFormBundle\Model\Contact();
        $contact->setName($registrant->name);
        $contact->setLastName($registrant->lastName);
        $contact->setIdContact($registrant->idContact);
        //cas particulier pour les formulaires sans code pays
        //France: on remplace le +33 par 0
        $contact->setPhone(str_replace($registrant->countryCode->code, 0, $registrant->phone));
        $additionalFields = $this->buildArrayAdditionalFields($registrant->additionalFields);
        $contact->setDateRegistration($additionalFields[$this->fields['datecreation']]);
        $contact->setDateModification($additionalFields[$this->fields['datemodification']]);
        $contact->setUsername($additionalFields[$this->fields['login']]);
        $contact->setPassword($additionalFields[$this->fields['password']]);
        $contact->setDataContact($this->buildDataContact($additionalFields));
        return $contact;
    }

    /**
     * Cette méthode permet de reconstruire l'attribut dataContact de la classe
     * contact une fois que l'utilisateur a rentré son login et son mot de passe
     * @param array $additionalFields tableau associatif des champs additionnels
     * @return \Clever\CleverSMSFormBundle\Model\DataContact Retourne une instance de DataContact
     */
    public function buildDataContact($additionalFields) {

        $dataContact = new DataContact();
        $dataContact->setEmail($additionalFields[$this->fields['email']]);
        $dataContact->setOld($additionalFields[$this->fields['old']]);
        $dataContact->setCity($additionalFields[$this->fields['city']]);
        $dataContact->setAddress($additionalFields[$this->fields['address']]);
        $dataContact->setSubscriberNumber($additionalFields[$this->fields['subscriberNumber']]);
        $dataContact->setTitleOfTransport($additionalFields[$this->fields['titleOfTransport']]);
        $dataContact->setLandlinePhone($additionalFields[$this->fields['landlinePhone']]);
        $dataContact->setFrequencyOfUse($additionalFields[$this->fields['frequencyOfUse']]);
        $dataContact->setGender($additionalFields[$this->fields['civilite']]);
        $bus = array();
        foreach ($this->fields['bus'] as $value) {
            if ($additionalFields[$value] == 'on') {
                $bus[] = $value;
            }
        }
        $dataContact->setBus($bus);
        return $dataContact;
    }

   /**
     * Crée un objet FormEmail. Cet objet servira d'envoyer un mail d'inscription.
     * @param \Contact $contact 
     * @param string $login Identifiant créé pour le contact
     * @param string $password Mot de passe créé pour le contact
     * @return \Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\FormEmail|null Retourne une instance de Formail si l'adresse
     * email n'est pas null ou vide.
     */
    public function buildEmail(\Clever\CleverSMSFormBundle\Model\Contact $contact, $content,$subjet) {
        $email = $contact->getDataContact()->getEmail();
        if ($email != null && $email != '') {
            $formEmail = new FormEmail();
            $formEmail->setTo($email);
            $formEmail->setSubject($subjet);
            $formEmail->setFrom($this->formGeneralInfos['EmailFrom']);
            $formEmail->setPersonal($this->formGeneralInfos['personal']);
            $formEmail->setReplyTo($this->formGeneralInfos['reply-To']);
            $formEmail->setMessage($content);
            return $formEmail;
        }
        return null;
    }

    /**
     * 
     * @param \Clever\CleverSMSFormBundle\Model\Contact $contact
     */
    private function getEmailRegistrationContent(\Clever\CleverSMSFormBundle\Model\Contact $contact) {
        $email = "<div style=\"font-family:Verdana; font-size:11px;\">";
        $email .= "Bonjour  ";
        $email .= $contact->getName() . ' ' . $contact->getLastName();
        $email .= ",<br /><br />";
        $email .= "Nous vous confirmons votre inscription au service gratuit des informations  " . $this->formGeneralInfos['label_name'] . ".<br />";
        $email .= "Voici les param&egrave;tres d'acc&egrave;s &agrave; votre interface d'abonn&eacute; : <br /> - Adresse de connexion : <a href=\"" . $this->formGeneralInfos['url'] . "\">" . $this->formGeneralInfos['url'] . "</a> <br /> - Identifiant :" . $contact->getUsername() . " <br /> - Mot de passe :" . $contact->getPassword() . "<br />";
        $email .= "Conservez pr&eacute;cieusement ce message afin de pouvoir modifier votre compte ult&eacute;rieurement.<br /><br /> &Agrave; tr&egrave;s bient&ocirc;t.";
        $email .= "</div>";
        return $email;
    }

    private function getEmailPasswordLostContent(\Clever\CleverSMSFormBundle\Model\Contact $contact) {

        $email = "<div style=\"font-family:Verdana; font-size:11px;\">";
        $email.= "Bonjour<br/><br/>";
        $email .= "Param&egrave;tres d'acc&egrave;s &agrave; l'interface abonn&eacute; : <br /> - Lien : <a href=\"" . $this->formGeneralInfos['url'] . "\">" . $this->formGeneralInfos['url'] . "</a> <br /> - Identifiant : " . $contact->getUsername() . " <br /> - Mot de passe : " . $contact->getPassword() . "<br />";
        $email.= "Conservez pr&eacute;cieusement ce message afin de pouvoir modifier votre compte ult&eacute;rieurement.<br /><br /> &Agrave; tr&egrave;s bient&ocirc;t.";
        $email .= "</div>";
        return $email;
    }

    /**
     *  Crée un objet formSMS. Cette objet servira à envoyer le SMS d'inscription.
     * @param \Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\Contact $contact
     * @param string $username Identifiant de l'inscrit
     * @param string $password Mot de passe de l'inscrit
     * @return \Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\FormSMS|null
     */

    public function buildSMS(\Clever\CleverSMSFormBundle\WebServiceContactProxy\Model\Contact $contact,$username,$password) {

        $phone = $contact->getPhone();
        if ($phone != null && $phone != '') {
            $formSMS = new FormSMS();
            $formSMS->setNumGSM($phone);
            $sms = "Bonjour  " . $contact->getName() . " " . $contact->getLastName() . " ";
            $sms.= "Vous êtes bien inscrit au service alerte " . $this->formGeneralInfos['label_name'] . ".Vos identifiant et mot de passe sont :" . $username . " et " . $password;
            $formSMS->setMessage($sms);
            return $formSMS;
        }
        return null;
    }


    /**
     * Cette fonction met le numéro de téléphone au format international.
     * @param string $number Numero de téléphone.
     * @return string Retourne le numéro transformé.
     */
    private function putNumberToInternational($number) {

        $search = array(".", "-", " ", "\t");
        $replace = array("", "", "", "");
        $number = str_replace($search, $replace, $number);
        if (substr($number, 0, 1) == '+') {
            return $number;
        }
        if (substr($number, 0, 2) == '00') {
            $number = '+' . substr($number, 2);
            return $number;
        } elseif (substr($number, 0, 1) == '0') {
            $number = $this->formGeneralInfos['defaultCountrycode'] . substr($number, 1);
            return $number;
        } elseif (is_numeric(substr($number, 0, 1))) {
            return $this->formGeneralInfos['defaultCountrycode'] . $number;
        }
    }

    /**
     * Cette méthode permet d'encoder le mot de passe de l'inscrit en base 64.
     * @param string $string Le mot de passe.
     * @return Retourne le mot de passe encrypté.
     */
    private function encode($string) {
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(self::$key), $string, MCRYPT_MODE_CBC, md5(md5(self::$key))));
        return $encrypted;
    }

    /**
     * Cette méthode permet de décoder le mot de passe de l'inscrit.
     * @param string $encrypted le mot de passe de l'inscrit.
     * @return string Retourne le mot de passe décrypté.
     */
    private function decode($encrypted) {
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(self::$key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5(self::$key))), "\0");
        return $decrypted;
    }

    /**
     * La classe contact retournée par les web services contient un tableau d'objet 'additionalField'.
     * Cette méthode permet de mettre dans un tableau associatif les champs additionnels et leurs valeurs.
     * @param array $arrayAdditionalFields Attribut champs additionnels.
     * @return array Retourne le tableau assiociatif des champs additionnels.
     */
    private function buildArrayAdditionalFields($arrayAdditionalFields) {
        $fields = array();
        foreach ($arrayAdditionalFields as $additionalField) {
            $fields[$additionalField->fieldName] = (!isset($additionalField->value)) ? '' : $additionalField->value;
        }
        return $fields;
    }

}
