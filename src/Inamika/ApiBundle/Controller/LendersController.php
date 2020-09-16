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

use Inamika\BackEndBundle\Entity\Lender;
use Inamika\BackOfficeBundle\Form\Lender\LenderType;

class LendersController extends FOSRestController
{
    public function indexAction(){
        return $this->handleView($this->view($this->getDoctrine()->getRepository(Lender::class)->getAll()->getQuery()->getResult()));
    }
    
    public function searchAction(Request $request){
        $query=$request->query->get('query',null);
        $offset=$request->query->get('offset',0);
        $limit=$request->query->get('limit',30);
        return $this->handleView($this->view(array(
            'results'=>$this->getDoctrine()->getRepository(Lender::class)->search($query,$limit,$offset)->getQuery()->getResult(),
            'total'=>$this->getDoctrine()->getRepository(Lender::class)->searchTotal($query,$limit,$offset),
            'offset'=>$offset,
            'limit'=>$limit
        )));
    }
    
    public function getAction($id){
        if(!$entity=$this->getDoctrine()->getRepository(Lender::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        return $this->handleView($this->view($entity));
    }

    public function postAction(Request $request){
        $entity = new Lender();
        $form = $this->createForm(LenderType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }
    
    public function putAction(Request $request,$id){
        if(!$entity=$this->getDoctrine()->getRepository(Lender::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $form = $this->createForm(LenderType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function deleteAction(Request $request,$id){
        if(!$entity=$this->getDoctrine()->getRepository(Lender::class)->find($id))
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