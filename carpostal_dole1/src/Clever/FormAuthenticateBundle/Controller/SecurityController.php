<?php

namespace Clever\FormAuthenticateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller {

    public function loginAction() {
        $request = $this->getRequest();
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {

            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {

            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
       
        return $this->render('CleverFormAuthenticateBundle:Security:login.html.twig', array(
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
        ));
    }
    
   public  function sendPasswordAction() {
       
       $request= $this->getRequest();
       if($request->getMethod()=='POST'){
            $logger = $this->get('logger');
            try {
              
                    $username = $request->request->get('pwd_lost');
                    $service = $this->get('wscontact');                 
                    $registrant=$service->findARegistrantByLogin($username);
                    $contact=$service->buildContact($registrant);
                    $rs=$service->sendPassword($contact);
                    $key= ($rs==true)?'sendemail':'noadress';
                    $this->get('session')->getFlashBag()->add('contact', $key);
                    return $this->render('CleverFormAuthenticateBundle:Security:sendPassordForm.html.twig',array('email'=>$contact->getDataContact()->getEmail()));
            }
             catch (\SoapFault $e) {
                    $logger->error("Erreur lors de la demande de mot de passe");
                    //numero de téléphone rejété par les WS
                    if (strpos($e->faultstring, 'ContactNotFoundException') !== FALSE) {
                        $logger->error($e->faultstring);
                        $this->get('session')->getFlashBag()->add('contact', 'notFound');
                        //contact existant
                    } else {
                        $logger->error($e->faultstring);
                        $this->get('session')->getFlashBag()->add('contact', 'error');
                    }
                }
                 return $this->render('CleverFormAuthenticateBundle:Security:sendPassordForm.html.twig');
       }
       return $this->redirect($this->generateUrl('login'));
    }
    
    

}
