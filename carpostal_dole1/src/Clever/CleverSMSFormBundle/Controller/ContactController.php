<?php

namespace Clever\CleverSMSFormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Clever\CleverSMSFormBundle\Model\Contact;
use Clever\CleverSMSFormBundle\Form\ContactType;

class ContactController extends Controller {

    /**
     * Fonction qui gère la création de contact
     * @return Response vue
     */
    public function registerAction() {
        //On crée une nouvelle instance de contact qu'on ratache au formulaire de contact
        $fields = $this->container->getParameter('Fields');
       
        $contact = new Contact();
        $form = $this->createForm(new ContactType($fields), $contact);
       
        //On récupère la requête
        $request = $this->getRequest();
        //On vérifie qu'elle est de type POST
        if ($request->getMethod() == "POST") {
            //on rattache le formulaire à la requête
            
            $form->bind($request);
            if ($form->isValid()) {
                //on récupère le service Logger
                $logger = $this->get('logger');
                $identity = $contact->getName() . ' ' . $contact->getLastName();
                $logger->info($identity . ' a soumis un formulaire valide');
                try {
                    $contact->setDateRegistration(date('Y-m-d H:i:s'));
                    //récuperation du service de creation de contact
                    $service = $this->get('wscontact');
                    $logger->info("Début du traitement pour " . $identity);
                    //création du contact via WebService
                    $idContact = $service->createAContact($contact);
                    $logger->info("Contact créé avec succès ID : " . $idContact);
                    $this->get('session')->getFlashBag()->add('contact', 'creation');
                } catch (\SoapFault $e) {
                    $logger->error("Erreur lors de l'inscription de " + $identity);
                    //numero de téléphone rejété par les WS
                    if (strpos($e->faultstring, 'InvalidPhoneNumberException') !== FALSE) {
                        $logger->error($e->faultstring);
                        $this->get('session')->getFlashBag()->add('contact', 'invalidNumber');
                        //contact existant
                    } else if (strpos($e->faultstring, 'ContactExistException') !== FALSE) {
                        $logger->error($e->faultstring);
                        $this->get('session')->getFlashBag()->add('contact', 'double');
                    } else {
                        $logger->error($e->faultstring);
                        $this->get('session')->getFlashBag()->add('contact', 'error');
                    }
                }
                return $this->render('CleverCleverSMSFormBundle:Contact:registerResult.html.twig');
            }
            //le formulaire n'est pas valide
            else {
                return $this->render('CleverCleverSMSFormBundle:Contact:formContent.html.twig', array('form' => $form->createView(), 'errors' => $form->getErrors(), 'submit' => "S'inscrire"));
            }
        }
        return $this->render('CleverCleverSMSFormBundle:Contact:register.html.twig', array('form' => $form->createView(), 'errors' => $form->getErrors(), 'submit' => "S'inscrire"));
    }

    /**
     * Fonction qui gère la mise à jour et la désinscription du contact.
     * @return Response vue
     */
    public function updateAction() {
        // On récupère le service de securité
        $security = $this->get('security.context');
        // On récupère le token
        $token = $security->getToken();
        // On récupère l'utilisateur
        $contact = $token->getUser();
        //On crée une nouvelle instance de contact qu'on ratache au formulaire de contact
        $fields = $this->container->getParameter('Fields');
        $form = $this->createForm(new ContactType($fields), $contact);
        //On récupère la requête
        $request = $this->getRequest();
        //On vérifie qu'elle est de type POST
        if ($request->getMethod() == "POST") {
            //on rattache le formulaire à la requête
            $form->bind($request);
            //On vérifie que les valeurs entrées sont valides
            if ($form->isValid()) {
                //on récupère le service Logger
                $logger = $this->get('logger');
                $identity = $contact->getName() . ' ' . $contact->getLastName();
                $logger->info($identity . ' a soumis un formulaire valide');
                try {
                    $service = $this->get('wscontact');
                    $logger->info("Début du traitement pour " . $identity);
                    //suppression du contact
                    if ($contact->getDataContact()->getUnregister() == 1) {
                         $logger->info("Demande de désinscription de :  " . $identity);
                        $service->removeAContact($contact->getIdContact());
                        $this->get('session')->getFlashBag()->add('contact', 'suppression');
                    }
                    //mis àjour du contact
                    else {
                        $contact->setDateModification(date('Y-m-d H:i:s'));
                        
                        //récuperation du service de creation de contact               
                        //création du contact via WebService
                        $service->updateAContact($contact);
                        $logger->info("Contact mis à jour avec succès");
                        $this->get('session')->getFlashBag()->add('contact', 'update');
                    }
                } catch (\SoapFault $e) {
                    $logger->error("Erreur lors de la mise à jour de " + $identity);
                    //numero de téléphone rejété par les WS
                    if (strpos($e->faultstring, 'InvalidPhoneNumberException') !== FALSE) {
                        $logger->error($e->faultstring);
                        $this->get('session')->getFlashBag()->add('contact', 'invalidNumber');
                        //contact existant
                    } else {
                        $logger->error($e->faultstring);
                        $this->get('session')->getFlashBag()->add('contact', 'error');
                    }
                }
                return $this->render('CleverCleverSMSFormBundle:Contact:updateResult.html.twig');
            }
            //le formulaire n'est pas valide
            else {
                return $this->render('CleverCleverSMSFormBundle:Contact:formContent.html.twig', array('form' => $form->createView(), 'errors' => $form->getErrors(), 'submit' => 'Envoyer'));
            }
        }
        return $this->render('CleverCleverSMSFormBundle:Contact:update.html.twig', array('form' => $form->createView(), 'errors' => $form->getErrors(), 'submit' => 'Envoyer'));
    }
   
    //    Gestion des anciens liens disponibles sur les formulaires
   public function registerDefaultredirectAction(){
      return  $this->redirect($this->generateUrl('register'));
        
    }
    
    public function updateDefaultredirectAction(){
        
          return  $this->redirect($this->generateUrl('update'));
    }

}
