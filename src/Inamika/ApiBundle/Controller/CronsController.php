<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackEndBundle\Entity\Offer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Config\Definition\Exception\Exception;


class CronsController extends DefaultController
{
    public function picturesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        foreach($products as $key=>$product){
            $path=$this->setting["uploads_directory"];
            $fileName=$product->getCode().".jpg";
            if(file_exists($path.$fileName)){
                $resizes=$this->setting['resize'];
                foreach($resizes as $resize){
                    $this->get('image.handling')->open($path.$fileName)
                    ->resize($resize['width'],$resize['height'])
                    ->save($resize['path'].$fileName);
                }
                $product->setPicture($fileName);
                $em->persist($product);
            }
        }
        $em->flush();
        return $this->handleView($this->view(array("OK"), Response::HTTP_OK));
    }
    
    public function offersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $offers=$this->getDoctrine()->getRepository(Offer::class)->findAll();
        foreach($offers as $key=>$offer){
            $path=$this->setting["uploads_directory"];
            $fileName=$offer->getPicture();
            if(file_exists($path.$fileName)){
                $resizes=$this->setting['resize'];
                foreach($resizes as $resize){
                    $this->get('image.handling')->open($path.$fileName)
                    ->resize($resize['width'],$resize['height'])
                    ->save($resize['path'].$fileName);
                }
                $offer->setPicture($fileName);
                $em->persist($offer);
            }
        }
        $em->flush();
        return $this->handleView($this->view(array("OK"), Response::HTTP_OK));
    }

}
