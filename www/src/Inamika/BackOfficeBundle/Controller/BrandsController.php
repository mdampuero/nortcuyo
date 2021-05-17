<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Brand;
use Inamika\BackOfficeBundle\Form\Brand\BrandType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BrandsController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Brands:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_brands_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(){
        return $this->render('InamikaBackOfficeBundle:Brands:form.html.twig',array(
            'form' => $this->createForm(BrandType::class, new Brand(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_brands_post')
            ))->createView()
        ));
    }	

    public function editAction($id){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:Brand')->find($id);
        return $this->render('InamikaBackOfficeBundle:Brands:form.html.twig',array(
            'entity'=>$entity,
            'form' => $this->createForm(BrandType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_brands_put',array('id'=>$id))
            ))->createView()
        ));
    }
}
