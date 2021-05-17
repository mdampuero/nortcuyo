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

use Inamika\BackEndBundle\Entity\Customer;
use Inamika\BackEndBundle\Form\Login\LoginType;


class LoginController extends FOSRestController
{   
    public function postAction(Request $request){
        $form = $this->createForm(LoginType::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            if(!$entity=$this->getDoctrine()->getRepository(Customer::class)->findOneBy(array(
                'email'=>$form->get('username')->getData(),
                'password'=>substr(md5($form->get('password')->getData()), 0, 16)
            )))
                return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
            $dataCustomer=[
                'customer'=>$entity,
                'category'=>$entity->getCustomerCategory(),
                'cart'=>[],
                'isSeller'=>$entity->getIsSeller(),
            ];
            $this->get('session')->set('_security_main', $dataCustomer);
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    
}


