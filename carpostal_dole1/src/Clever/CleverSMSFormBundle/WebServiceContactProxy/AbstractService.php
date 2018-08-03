<?php

namespace Clever\CleverSMSFormBundle\WebServiceContactProxy;

use Clever\CleverSMSFormBundle\WebServiceContactProxy\ClientSoap;

abstract class AbstractService {

    protected $client = null;

    public function __construct() {

        $this->client = new ClientSoap();

        if (property_exists($this, 'classmap'))
            $this->client->addOption('classmap', $this->classmap);

        $this->client->addOption('encoding', 'UTF-8');
    }

    public function __call($method, $args) {

        return $this->client->__call($method, $args);
    }

    public function setAuthentication($login, $password) {
        $this->client->addOption('login', $login);
        $this->client->addOption('password', $password);
    }

    public function addOption($name, $value) {
        $this->client->addOption($name, $value);
    }

    public function setWsdl($wsdl) {
        $this->client->setWsdl($wsdl);
    }

    public function setLocation($location) {
        $this->client->setWsdl(null);
        $this->client->setLocation($location);
    }
    
    public function setUri($uri){
        $this->client->addOption('uri', $uri);
    }

}