<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Slider;
use Inamika\BackOfficeBundle\Form\Slider\SliderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SlidersController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Sliders:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_sliders_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(){
        return $this->render('InamikaBackOfficeBundle:Sliders:form.html.twig',array(
            'form' => $this->createForm(SliderType::class, new Slider(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_sliders_post')
            ))->createView()
        ));
    }	

    public function editAction($id){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:Slider')->find($id);
        return $this->render('InamikaBackOfficeBundle:Sliders:form.html.twig',array(
            'entity'=>$entity,
            'form' => $this->createForm(SliderType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_sliders_put',array('id'=>$id))
            ))->createView()
        ));
    }
}
