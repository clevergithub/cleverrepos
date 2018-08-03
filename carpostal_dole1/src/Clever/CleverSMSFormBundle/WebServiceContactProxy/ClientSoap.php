<?php

namespace Clever\CleverSMSFormBundle\WebServiceContactProxy;

class ClientSoap {

    private $wsdl;
    private $options = array();

    /**
     * @var SoapClient
     */
    private $client = null;

    public function setWsdl($wsdl) {
        $this->wsdl = $wsdl;
    }

    public function setOptions(array $options) {
        $this->options = $options;
    }

    public function setLocation($location) {
        $this->addOption('location', $location);
    }

    public function addOption($key, $value) {
        $this->options[$key] = $value;
    }

    public function createSoapClient() {
//  
        $this->client = new \SoapClient($this->wsdl, $this->options);
    }

    public function __call($method, array $args) {
        if ($this->client == null)
            $this->createSoapClient();
        return  $this->client->__soapCall($method, $args);
    }

}

?>