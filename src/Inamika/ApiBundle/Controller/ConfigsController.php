<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\CustomerCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Config\Definition\Exception\Exception;


class ConfigsController extends DefaultController
{
    public function getAction(Request $request){
        $data=array(
            'defaultCategory'=>$this->getDoctrine()->getRepository(CustomerCategory::class)->findOneByIsDefault(true)
        );
        return $this->handleView($this->view($data, Response::HTTP_OK));
    }

}
