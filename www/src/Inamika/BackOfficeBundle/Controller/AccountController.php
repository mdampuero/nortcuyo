<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackOfficeBundle\Form\User\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller{

	protected $pathBase="backoffice_account";

	public function indexAction(){
        $entity=$this->get('security.token_storage')->getToken()->getUser();
		return $this->render('InamikaBackOfficeBundle:Account:index.html.twig',array(
            'entity'=>$entity,
			'form' => $this->createForm(ProfileType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_users_profile',array('id'=>$entity->getId()))
            ))->createView()
            )
        );
	}
}
