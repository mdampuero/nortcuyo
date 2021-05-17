<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Order;
use Inamika\BackOfficeBundle\Form\Order\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrdersController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Orders:index.html.twig',array(
            'currencies'=>$this->getDoctrine()->getRepository('InamikaBackEndBundle:Currency')->getAll()->getQuery()->getResult(),
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_orders_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }
    
    public function viewAction($id){
		return $this->render('InamikaBackOfficeBundle:Orders:view.html.twig',array('data'=>$this->getDoctrine()->getRepository('InamikaBackEndBundle:Orders')->find($id)));
    }
}
