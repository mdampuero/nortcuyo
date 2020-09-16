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

use Inamika\BackEndBundle\Entity\Product;


class CartController extends FOSRestController
{   
    public function postAction(Request $request){
        $dataCustomer=$this->get('session')->get('_security_main');
        $content=json_decode($request->getContent(), true);
        if(!isset($content["product"]) || empty($content["product"]) || !isset($content["quantity"]) || empty($content["quantity"]))
            return $this->handleView($this->view(null, Response::HTTP_BAD_REQUEST));
        $product=$this->getDoctrine()->getRepository(Product::class)->findOneByCode($content["product"]);
        if(!$product)
            return $this->handleView($this->view(null, Response::HTTP_BAD_REQUEST));
        $dataCustomer["cart"][$content["product"]]=['quantity'=>(int)$content["quantity"],'product'=>$product];
        $this->get('session')->set('_security_main', $dataCustomer);
        return $this->handleView($this->view($dataCustomer, Response::HTTP_OK));
    }

    public function deleteAction(Request $request){
        $dataCustomer=$this->get('session')->get('_security_main');
        $content=json_decode($request->getContent(), true);
        if(!isset($content["product"]) || empty($content["product"]))
            return $this->handleView($this->view(null, Response::HTTP_BAD_REQUEST));
        $product=$this->getDoctrine()->getRepository(Product::class)->findOneByCode($content["product"]);
        unset($dataCustomer["cart"][$content["product"]]);
        $this->get('session')->set('_security_main', $dataCustomer);
        return $this->handleView($this->view($dataCustomer, Response::HTTP_OK));
    }
    
    public function emptyAction(Request $request){
        $dataCustomer=$this->get('session')->get('_security_main');
        $dataCustomer["cart"]=[];
        $this->get('session')->set('_security_main', $dataCustomer);
        return $this->handleView($this->view($dataCustomer, Response::HTTP_OK));
    }

    
}


