<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\OrdersStatus;

class OrdersStatusController extends FOSRestController
{   
    public function indexAction(Request $request)
    {
        return $this->handleView(
            $this->view($this->getDoctrine()->getRepository(OrdersStatus::class)->findByIsDelete(false)
        ));
    }
}