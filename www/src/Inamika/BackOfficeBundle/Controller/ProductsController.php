<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackOfficeBundle\Form\Product\ProductType;
use Inamika\BackOfficeBundle\Form\Product\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductsController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Products:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_products_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(){
        return $this->render('InamikaBackOfficeBundle:Products:form.html.twig',array(
            'form' => $this->createForm(ProductType::class, new Product(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_products_post')
            ))->createView()
        ));
    }	
    
    public function uploadAction(){
		return $this->render('InamikaBackOfficeBundle:Products:upload.html.twig',array(
            'form' => $this->createForm(UploadType::class, null,array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_products_upload')
            ))->createView()
        ));
    }	

    public function editAction($id){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:Product')->find($id);
        return $this->render('InamikaBackOfficeBundle:Products:form.html.twig',array(
            'entity'=>$entity,
            'form' => $this->createForm(ProductType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_products_put',array('id'=>$id))
            ))->createView()
        ));
    }
}
