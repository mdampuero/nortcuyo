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

use Inamika\BackEndBundle\Entity\Slider;
use Inamika\BackOfficeBundle\Form\Slider\SliderType;
use Inamika\BackEndBundle\Entity\Orders;

class SlidersController extends DefaultController
{   
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', array());
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        return $this->handleView($this->view(array(
            'data' => $this->getDoctrine()->getRepository(Slider::class)->search($search["value"], $limit, $offset, $sort, $direction)->getQuery()->getResult(),
            'recordsTotal' => $this->getDoctrine()->getRepository(Slider::class)->total(),
            'recordsFiltered' => $this->getDoctrine()->getRepository(Slider::class)->searchTotal($search["value"], $limit, $offset),
            'offset' => $offset,
            'limit' => $limit,
        )));
    }
    
    public function getAction($id){
        if(!$entity=$this->getDoctrine()->getRepository(Slider::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        return $this->handleView($this->view($entity));
    }

    public function postAction(Request $request){
        $entity = new Slider();
        $form = $this->createForm(SliderType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('pictureBase64')->getData())
                $entity->setPicture($this->base64ToFile($form->get('pictureBase64')->getData(),"uploads/"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }
    
    public function putAction(Request $request,$id){
        if(!$entity=$this->getDoctrine()->getRepository(Slider::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $pictureOld=$entity->getPicture();
        $form = $this->createForm(SliderType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('pictureBase64')->getData())
                $entity->setPicture($this->base64ToFile($form->get('pictureBase64')->getData(),"uploads/"));
            else{
                if($form->get('pictureRemove')->getData()) $pictureOld=null;
                $entity->setPicture($pictureOld);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function deleteAction(Request $request,$id){
        if(!$entity=$this->getDoctrine()->getRepository(Slider::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $form = $this->createFormBuilder(null, array('csrf_protection' => false))->setMethod('DELETE')->getForm();
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()){
            $entity->setIsDelete(true);
            $this->getDoctrine()->getManager()->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

}