<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WSContactProvider
 *
 * @author clever
 */

namespace Clever\FormAuthenticateBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Clever\CleverSMSFormBundle\WebServiceContactProxy\ContactService;

class WSContactProvider implements UserProviderInterface {

    private $service;

    public function __construct(ContactService $service) {
        $this->service = $service;
    }

    public function loadUserByUsername($username) {

        try {
            //on charge le contact à partir de son identifiant
            $registrant = $this->service->findARegistrantByLogin($username);
            $contact = $this->service->buildContact($registrant);
            //on envoie la réponse au kernel pourqu'il s'occupe de l'authentification
            return $contact;
        } catch (\Exception $ex) {
           
            throw new UsernameNotFoundException(sprintf('No record found for user %s', $username));
        }
    }

    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return $class === 'Clever\CleverSMSFormBundle\Model\Contact';
    }

}
