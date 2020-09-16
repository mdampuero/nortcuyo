<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Inamika\BackEndBundle\Form\Contact\ContactType;
use Symfony\Component\Routing\Generator\UrlGenerator;

class DefaultController extends Controller{
    
    public function indexAction(){

        return $this->render('InamikaFrontendBundle:Default:index.html.twig',array(
            'form' => $this->createForm(ContactType::class, null,array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_contact_post')
            ))->createView()
        ));
    }

    public function brandsAction(){
        return $this->render('InamikaFrontendBundle:Default:brands.html.twig',array('data'=>$this->get('ApiCall')->get($this->generateUrl('api_brands',[],UrlGenerator::ABSOLUTE_URL)."?search%5Bvalue%5D=&start=0&length=100&sort=order&direction=ASC")));
    }
    
    public function catalogAction(){
        return $this->render('InamikaFrontendBundle:Default:catalog.html.twig',array('data'=>$this->get('ApiCall')->get($this->generateUrl('api_brands_catalog',[],UrlGenerator::ABSOLUTE_URL))));
    }
    
    public function slidersAction(){
        return $this->render('InamikaFrontendBundle:Default:sliders.html.twig',array('data'=>$this->get('ApiCall')->get($this->generateUrl('api_sliders',[],UrlGenerator::ABSOLUTE_URL)."?search%5Bvalue%5D=&start=0&length=100&sort=order&direction=ASC")));
    }
}
