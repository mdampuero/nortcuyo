<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackEndBundle\Entity\ProductPicture;
use Inamika\BackEndBundle\Entity\ProductCategory;
use Inamika\BackOfficeBundle\Form\Product\ProductType;
use Inamika\BackOfficeBundle\Form\Product\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Config\Definition\Exception\Exception;


class ProductsController extends DefaultController
{
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', array());
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        return $this->handleView($this->view(array(
            'data' => $this->getDoctrine()->getRepository(Product::class)->search($search["value"], $limit, $offset, $sort, $direction)->getQuery()->getResult(),
            'recordsTotal' => $this->getDoctrine()->getRepository(Product::class)->total(),
            'recordsFiltered' => $this->getDoctrine()->getRepository(Product::class)->searchTotal($search["value"], $limit, $offset),
            'offset' => $offset,
            'limit' => $limit,
        )));
    }

    public function getAction($code){
        if (!$entity = $this->getDoctrine()->getRepository(Product::class)->findOneByCode($code))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        return $this->handleView($this->view($entity));
    }
    
    public function relatedAction($code){
        return $this->handleView($this->view($this->getDoctrine()->getRepository(Product::class)->getRelated($code)->getQuery()->getResult()));
    }

    public function postAction(Request $request){
        $entity = new Product();
        $form = $this->createForm(ProductType::class, $entity);
        $content=json_decode($request->getContent(), true);
        $form->submit($content);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($form->get('pictureBase64')->getData())
                $entity->setPicture($this->base64ToFile($form->get('pictureBase64')->getData(),"uploads/"));

            for ($i=1; $i <5 ; $i++) { 
                if(!empty($content['picture_'.$i.'Base64'])){
                    $picture=new ProductPicture();
                    $picture->setName($this->base64ToFile($content['picture_'.$i.'Base64'],"uploads/"));
                    $picture->setProduct($entity);
                    $picture->setPosition($i);
                    $em->persist($picture);
                }
            }
            if($content["category"])
                $entity->setCategory($this->getDoctrine()->getRepository(ProductCategory::class)->find($content["category"]));
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function putAction(Request $request, $id)
    {
        if (!$entity = $this->getDoctrine()->getRepository(Product::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $pictureOld=$entity->getPicture();
        $form = $this->createForm(ProductType::class, $entity);
        $content=json_decode($request->getContent(), true);
        $form->submit($content);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($form->get('pictureBase64')->getData())
                $entity->setPicture($this->base64ToFile($form->get('pictureBase64')->getData(),"uploads/"));
            else{
                if($form->get('pictureRemove')->getData()) $pictureOld=null;
                $entity->setPicture($pictureOld);
            }
            for ($i=1; $i <5 ; $i++) { 
                if(!empty($content['picture_'.$i.'Base64'])){
                    if(!$picture = $this->getDoctrine()->getRepository(ProductPicture::class)->findOneBy(array('product'=>$entity,'position'=>$i)))
                    $picture=new ProductPicture();

                    $picture->setName($this->base64ToFile($content['picture_'.$i.'Base64'],"uploads/"));
                    $picture->setProduct($entity);
                    $picture->setPosition($i);
                    $em->persist($picture);
                }else{
                    if($content['picture_'.$i.'Remove']){
                        $picture = $this->getDoctrine()->getRepository(ProductPicture::class)->findOneBy(array('product'=>$entity,'position'=>$i));
                        $em->remove($picture);
                    }
                }
            }

            if($content["category"])
                $entity->setCategory($this->getDoctrine()->getRepository(ProductCategory::class)->find($content["category"]));
            $em->persist($entity);
            $em->flush();

            $pictures=$this->getDoctrine()->getRepository(ProductPicture::class)->findBy(array('product'=>$entity));
            foreach ($pictures as $key => $pic) {
                $pic->setPosition($key+1);
                $em->persist($pic);
            }
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function deleteAction(Request $request, $id)
    {
        if (!$entity = $this->getDoctrine()->getRepository(Product::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));

        $form = $this->createFormBuilder(null, array('csrf_protection' => false))->setMethod('DELETE')->getForm();
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setIsDelete(true);
            $this->getDoctrine()->getManager()->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }
}
