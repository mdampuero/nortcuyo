<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Form\Contact\ContactType;

class ContactController extends FOSRestController
{
    public function postAction(Request $request){
        $form = $this->createForm(ContactType::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            /** Enviar email */
            $settings = $this->container->get('setting');
            $message = (new \Swift_Message($this->get('setting')->getData()->getTitle().' - Consulta desde la web '))
            ->setFrom(array($this->getParameter('mailer_user')=>$this->get('setting')->getData()->getTitle()))
            ->setTo($this->get('setting')->getData()->getOrdersEmail())
            ->setBody($this->renderView('InamikaBackEndBundle:Emails:contact.html.twig', array('content' => json_decode($request->getContent(), true))),'text/html');
            $this->get('mailer')->send($message);
            /** Fin enviar email */
            return $this->handleView($this->view("dsa", Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

}