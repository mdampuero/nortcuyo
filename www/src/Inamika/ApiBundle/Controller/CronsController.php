<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackEndBundle\Entity\Cron;
use Inamika\BackEndBundle\Entity\Products;
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
    
    public function preciosClarosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if(!$cron=$em->getRepository(Cron::class)->findOneBy(['isDelete'=>false,'id'=>'16474a68-0e6d-11eb-8905-3a54f5d23ebe'])){
           
            exit("envio email");
            /** Enviar email */
            $message = (new \Swift_Message($this->get('setting')->getData()->getTitle().' - Categoria terminada #'))
            ->setFrom(array($this->getParameter('mailer_user')=>$this->get('setting')->getData()->getTitle()))
            ->setTo('mdampuero@gmail.com')
            ->setBody($this->renderView('InamikaBackOfficeBundle:Emails:Crons/categoriaFull.html.twig'),'text/html');
            $this->get('mailer')->send($message);
            /** Fin enviar email */
        }
        /* mendoza*/
        //$sucursales="10-2-282,10-2-290,19-1-02744,17-1-69,17-1-15,17-1-38,10-2-285,9-1-188,9-1-400,2011-1-92,19-1-02701,10-2-283,17-1-116,10-1-29,9-1-174,2011-1-64,9-1-402,17-1-52,2011-1-165,9-1-170,3001-1-122,9-3-5222,9-1-196,17-1-21,10-2-287,17-1-254,11-5-3613,9-1-181,9-1-857,16-2-6104";
        /* cordobba*/
        //$sucursales="1002-1-2,10-2-189,10-3-549,10-3-250,1002-1-6,10-3-375,15-1-454,10-3-353,15-1-261,15-1-544,10-3-655,10-3-338,1002-1-4,10-3-730,15-1-464,10-3-304,15-1-400,10-2-148,1002-1-1,10-3-308,1002-1-3,15-1-695,10-3-635,10-3-682,10-3-302,10-3-303,10-3-583,10-3-571,10-3-379,10-3-213";
        /* rosario*/
        //$sucursales="23-1-6256,2008-1-424,12-1-95,2011-1-115,10-1-32,19-1-00983,10-1-41,23-1-6262,19-1-00973,10-1-268,2011-1-83,12-1-165,2002-1-101,2003-1-7290,2011-1-29,12-1-96,12-1-99,2003-1-7550,19-1-03296,23-1-6260,12-1-97,2011-1-70,19-1-00812,2011-1-23,2011-1-155,9-3-5218,23-1-6257,3001-1-118,19-1-00989,19-1-00959";
        /* la palta */
        $sucursales= "10-2-109,2003-1-7780,2003-1-7120,2004-1-15,15-1-5441,3-1-66,9-1-36,12-1-149,9-2-62,10-2-144,2008-1-191,15-1-5410,15-1-5366,3-1-65,3-1-1344,15-1-1034,19-1-00525,15-1-5396,44-1-8,25-1-1,9-1-636,9-2-42,9-1-633,44-1-7,15-1-1020,15-1-5435,44-1-2,15-1-5402,3000-2-5022,10-1-8";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_URL, "https://d3e6htiiul5ek9.cloudfront.net/prod/productos?array_sucursales=".$sucursales."&offset=".$cron->getOffset()."&limit=".$cron->getLimit());
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: */*']);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING , 'gzip');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
        $result = curl_exec($ch);
        $resultArray=json_decode($result,TRUE);
        //echo '<pre>'.print_r($resultArray).'</pre>'; 
        if(key_exists('status',$resultArray) && $resultArray["status"]==200){
            $em = $this->getDoctrine()->getManager();
            if(count($resultArray["productos"])){
                foreach($resultArray["productos"] as $key => $product){
                    if(!$products=$em->getRepository(Products::class)->findOneByEan($product["id"])){
                        $products = new Products();
                        //$products->setCategory($cron->getCategory());
                        $products->setEan($product["id"]);
                        $products->setName($product["nombre"]);
                        $products->setPresentation($product["presentacion"]);
                        $products->setBrand($product["marca"]);
                    }
                    $products->setPriceMin($product["precioMin"]);
                    $products->setPriceMax($product["precioMax"]);
                    $products->setPrice($product["precioMax"]);
                    $em->persist($products);
                }
                $cron->setOffset($cron->getOffset()+$cron->getLimit());
            }else{
                $cron->setIsDelete(true);
            }
        }
        $cron->setUpdateAt(new \DateTime());
        $em->persist($cron);
        $em->flush();
        return $this->handleView($this->view(array("OK"), Response::HTTP_OK));
    }

    public function countAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $total=$em->getRepository(Products::class)->total();
        
        return $this->handleView($this->view(array(
            'crons'=>$em->getRepository(Cron::class)->findOneBy(['isDelete'=>false,'id'=>'16474a68-0e6d-11eb-8905-3a54f5d23ebe']),
            'totalProductos'=>$em->getRepository(Products::class)->total()
        ), Response::HTTP_OK));
    }

}
