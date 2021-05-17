<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\CustomerCategory;
use Inamika\BackOfficeBundle\Form\CustomerCategory\CustomerCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomerCategoriesController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:CustomerCategories:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_customerCategories_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(){
        return $this->render('InamikaBackOfficeBundle:CustomerCategories:form.html.twig',array(
            'form' => $this->createForm(CustomerCategoryType::class, new CustomerCategory(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_customerCategories_post')
            ))->createView()
        ));
    }	

    public function editAction($id){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:CustomerCategory')->find($id);
        return $this->render('InamikaBackOfficeBundle:CustomerCategories:form.html.twig',array(
            'entity'=>$entity,
            'form' => $this->createForm(CustomerCategoryType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_customerCategories_put',array('id'=>$id))
            ))->createView()
        ));
    }
}
