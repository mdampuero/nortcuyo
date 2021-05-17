<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\User;
use Inamika\BackOfficeBundle\Form\User\UserAddType;
use Inamika\BackOfficeBundle\Form\User\UserType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller{

	protected $pathBase="backoffice_users";

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Users:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_users_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView(),
        ));
	}

	public function addAction(){
		return $this->render('InamikaBackOfficeBundle:Users:form.html.twig',array(
			'form' => $this->createForm(UserAddType::class, new User(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_users_post')
            ))->createView()
        ));
	}	

	public function editAction($id){
        $entity=$this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render('InamikaBackOfficeBundle:Users:form.html.twig',array(
            'entity'=>$entity,
			'form' => $this->createForm(UserType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_users_put',array('id'=>$id))
            ))->createView()
        ));
	}
}
