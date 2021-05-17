<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Customer;
use Inamika\BackOfficeBundle\Form\Customer\CustomerType;
use Inamika\BackOfficeBundle\Form\Customer\CustomerEditType;
use Inamika\BackOfficeBundle\Form\Customer\BlankPassword;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomersController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Customers:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_customers_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView(),
            'formBlankPassword'=>$this->createForm(BlankPassword::class, new Customer(),array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_customers_blankPassword')
            ))->createView()
        ));
    }

    public function addAction(){
        return $this->render('InamikaBackOfficeBundle:Customers:form.html.twig',array(
            'form' => $this->createForm(CustomerType::class, new Customer(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_customers_post')
            ))->createView()
        ));
    }	

    public function editAction($id){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:Customer')->find($id);
        return $this->render('InamikaBackOfficeBundle:Customers:form.html.twig',array(
            'entity'=>$entity,
            'form' => $this->createForm(CustomerEditType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_customers_put',array('id'=>$id))
            ))->createView()
        ));
    }
}
