<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Setting;
use Inamika\BackOfficeBundle\Form\Setting\SettingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SettingsController extends Controller{

	public function indexAction(){
        $entity=$this->getDoctrine()->getRepository('InamikaBackEndBundle:Setting')->find('setting');
        return $this->render('InamikaBackOfficeBundle:Settings:form.html.twig',array(
            'entity'=>$entity,
			'form' => $this->createForm(SettingType::class, $entity,array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_demos_put',array('id'=>'setting'))
            ))->createView()
        ));
	}
}
