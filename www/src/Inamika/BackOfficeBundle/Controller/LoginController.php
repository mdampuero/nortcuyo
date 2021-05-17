<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Inamika\BackOfficeBundle\Form\Login\LoginType;
use Inamika\BackOfficeBundle\Form\Login\ForgotType;
use Inamika\BackOfficeBundle\Form\Login\RecoverType;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller{

    public function indexAction(Request $request, AuthenticationUtils $authUtils){
        if($this->getUser()) return $this->redirectToRoute('backoffice_homepage');
        
        if($authUtils->getLastAuthenticationError()) $this->addFlash("danger", $this->get('translator')->trans('EMAIL_OR_PASSWORD_INVALID'));
        return $this->render('InamikaBackOfficeBundle:Login:index.html.twig',array(
            'form'=>$this->createForm(LoginType::class, null,array(
                'method' => 'POST',
                'attr'=>array(
                    'class'=>'form-horizontal form-material',
                    'id'=>'loginform',
                    'novalidate'=>'novalidate'
                    )
                ))->createView(),
            'formForgot'=>$this->createForm(ForgotType::class, null,array('method' => 'POST','attr'=>array('class'=>'sendToApi'),'action' => $this->generateUrl('security_forgot')))->createView()
        ));
    }

    public function forgotAction(Request $request){
        $form = $this->createForm(ForgotType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){  
                $email=$form->get('email')->getData();
                $em=$this->getDoctrine()->getManager();
                $entity = $em->getRepository('InamikaBackEndBundle:User')->findOneBy(array('email'=>$email,'isDelete'=>false));
                if($entity){
                    $entity->setCodeActive(md5(md5(uniqid().uniqid())));
                    $em->persist($entity);
                    $em->flush();
                    $message = (new \Swift_Message($this->get('setting')->getData()->getTitle().' - '.$this->get('translator')->trans('RECOVERY_PASSWORD')))
                    ->setFrom(array($this->getParameter('mailer_user')=>$this->get('setting')->getData()->getTitle()))
                    ->setTo($entity->getEmail())
                    ->setBody($this->renderView('InamikaBackOfficeBundle:Emails:Login/forgotPassword.html.twig', array('entity' => $entity)),'text/html');
                    $this->get('mailer')->send($message); 
                    return new Response(json_encode(array('response' => true,'message'=>$this->get('translator')->trans('SEND_EMAIL_FORGOT_PASSWORD'))), 200, array('Content-Type' => 'application/json'));
                }else{
                    return new Response(json_encode(array('response' => false,'message'=>$this->get('translator')->trans('EMAIL_NOT_FOUND'))), 200, array('Content-Type' => 'application/json'));
                }
            }
        }
        return new Response(json_encode(array('response' => false,'message'=>$this->get('translator')->trans('EMAIL_INVALID'))), 200, array('Content-Type' => 'application/json'));
    }

    public function resetAction($id,$code,Request $request,UserPasswordEncoderInterface $passwordEncoder){
        if($this->getUser()) return $this->redirectToRoute('backoffice_homepage');
        $form = $this->createForm(RecoverType::class);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($entity = $em->getRepository('InamikaBackEndBundle:User')->findOneBy(array('id'=>$id,'codeActive'=>$code))){
            if ($form->isSubmitted()) {
                if($form->isValid()){
                    $password = $passwordEncoder->encodePassword($entity, $form->get('plainPassword')->getData());
                    $entity->setPassword($password);
                    $entity->setCodeActive(md5(md5(uniqid().uniqid())));
                    $em->persist($entity);
                    $em->flush();
                    $this->addFlash("success", $this->get('translator')->trans('RESET_PASSWORD_SUCCESS'));
                    return $this->redirectToRoute('security_login');
                }else{
                    $this->addFlash("danger", $this->get('translator')->trans('PASSWORD_NOT_VALID'));
                }
            }
        }else{
            $this->addFlash("danger", $this->get('translator')->trans('LINK_EXPIRED'));
            return $this->redirectToRoute('security_login');
        }
        return $this->render('InamikaBackOfficeBundle:Login:reset.html.twig', array(
            'form'=>$form->createView())
        );
    }

    public function logoutAction(){}
}
