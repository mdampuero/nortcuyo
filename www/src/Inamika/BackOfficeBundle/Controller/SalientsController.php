<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Salient;
use Inamika\BackOfficeBundle\Form\Salient\SalientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalientsController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Salients:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_salients_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(){
        return $this->render('InamikaBackOfficeBundle:Salients:form.html.twig',array(
            'form' => $this->createForm(SalientType::class, new Salient(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_salients_post')
            ))->createView()
        ));
    }	

    public function editAction($id){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:Salient')->find($id);
        return $this->render('InamikaBackOfficeBundle:Salients:form.html.twig',array(
            'entity'=>$entity,
            'products'=>json_encode($entity->getProducts(),true),
            'form' => $this->createForm(SalientType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_salients_put',array('id'=>$id))
            ))->createView()
        ));
    }
}
