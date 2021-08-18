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
use Inamika\BackEndBundle\Entity\Currency;
use Inamika\BackEndBundle\Entity\Customer;


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
        $dataCustomer["cart"]["items"][$content["product"]]=[
            'quantity'=>(int)$content["quantity"],
            'product'=>$product,
            'subtotal'=>$product->getPrice()*(int)$content["quantity"]*$dataCustomer["category"]->getDiscount(),
            'subtotalVat'=>$product->getPrice()*(int)$content["quantity"]*$product->getVat()*$dataCustomer["category"]->getDiscount(),
        ];
        
        $dataCustomer=$this->calcTotal($dataCustomer);
        $this->get('session')->set('_security_main', $dataCustomer);
        return $this->handleView($this->view($dataCustomer, Response::HTTP_OK));
    }
    
    public function putAction(Request $request){
        $dataCustomer=$this->get('session')->get('_security_main');
        $content=json_decode($request->getContent(), true);
        if(!isset($content["cart"]))
            return $this->handleView($this->view(null, Response::HTTP_BAD_REQUEST));
        $dataCustomer["cart"]["items"]=[];
        foreach($content["cart"] as $key=> $item){
            $product=$this->getDoctrine()->getRepository(Product::class)->findOneByCode($item["code"]);
            if(!$product)
                return $this->handleView($this->view(null, Response::HTTP_BAD_REQUEST));
            $dataCustomer["cart"]["items"][$item["code"]]=[
                'quantity'=>(int)$item["val"],
                'product'=>$product,
                'subtotal'=>$product->getPrice()*(int)$item["val"]*$dataCustomer["category"]->getDiscount(),
                'subtotalVat'=>$product->getPrice()*(int)$item["val"]*$product->getVat()*$dataCustomer["category"]->getDiscount(),
            ];
        }

        $dataCustomer=$this->calcTotal($dataCustomer);
        $this->get('session')->set('_security_main', $dataCustomer);
        return $this->handleView($this->view($dataCustomer, Response::HTTP_OK));
    }

    private function calcTotal($dataCustomer){
        $currencies=$this->getDoctrine()->getRepository(Currency::class)->getAll()->getQuery()->getResult();
        $totals=[];
        foreach ($currencies as $key => $currency) {
            $totals[$currency->getSymbol()]=array('total'=>0,'totalVat'=>0);
            foreach ($dataCustomer["cart"]["items"] as $key => $item) {
                if($item["product"]->getCurrency()->getId()==$currency->getId()){
                    $totals[$currency->getSymbol()]["total"]+=$item["subtotal"];
                    $totals[$currency->getSymbol()]["totalVat"]+=$item["subtotalVat"];
                }
            }
        }
        $dataCustomer["cart"]["totals"]=$totals;
        return $dataCustomer;
    }

    public function deleteAction(Request $request){
        $dataCustomer=$this->get('session')->get('_security_main');
        $content=json_decode($request->getContent(), true);
        if(!isset($content["product"]) || empty($content["product"]))
            return $this->handleView($this->view(null, Response::HTTP_BAD_REQUEST));
        $product=$this->getDoctrine()->getRepository(Product::class)->findOneByCode($content["product"]);
        unset($dataCustomer["cart"]["items"][$content["product"]]);
        $dataCustomer=$this->calcTotal($dataCustomer);
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


