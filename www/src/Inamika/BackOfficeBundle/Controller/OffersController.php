<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Offer;
use Inamika\BackOfficeBundle\Form\Offer\OfferType;
use Inamika\BackOfficeBundle\Form\Offer\OfferEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OffersController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Offers:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_offers_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(){
        return $this->render('InamikaBackOfficeBundle:Offers:form.html.twig',array(
            'form' => $this->createForm(OfferType::class, new Offer(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_offers_post')
            ))->createView()
        ));
    }	

    public function editAction($id){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:Offer')->find($id);
        return $this->render('InamikaBackOfficeBundle:Offers:form.html.twig',array(
            'entity'=>$entity,
            'form' => $this->createForm(OfferType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_offers_put',array('id'=>$id))
            ))->createView()
        ));
    }
}
