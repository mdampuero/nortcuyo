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

use Inamika\BackEndBundle\Entity\Customer;
use Inamika\BackOfficeBundle\Form\Customer\CustomerType;
use Inamika\BackOfficeBundle\Form\Customer\CustomerEditType;
use Inamika\BackEndBundle\Entity\Orders;

class CustomersController extends FOSRestController
{   
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', array());
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        return $this->handleView($this->view(array(
            'data' => $this->getDoctrine()->getRepository(Customer::class)->search($search["value"], $limit, $offset, $sort, $direction)->getQuery()->getResult(),
            'recordsTotal' => $this->getDoctrine()->getRepository(Customer::class)->total(),
            'recordsFiltered' => $this->getDoctrine()->getRepository(Customer::class)->searchTotal($search["value"], $limit, $offset),
            'offset' => $offset,
            'limit' => $limit,
        )));
    }
    
    public function getAction($id){
        if(!$entity=$this->getDoctrine()->getRepository(Customer::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        return $this->handleView($this->view($entity));
    }
   
    public function ordersAction($id, Request $request){
        if(!$entity=$this->getDoctrine()->getRepository(Customer::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        $total=$this->getDoctrine()->getRepository(Orders::class)->getByCustomerIdTotal($id);
        return $this->handleView($this->view(array(
            'data' => $this->getDoctrine()->getRepository(Orders::class)->getByCustomerId($id, $limit, $offset)->getQuery()->getResult(),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'offset' => $offset,
            'limit' => $limit,
        )));
    }
    
    public function refreshTokenAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        if(!$entity=$em->getRepository(Customer::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $content=json_decode($request->getContent(), true);
        if(isset($content["token"])){
            $entity->setToken($content["token"]);
            $em->persist($entity);
            $em->flush();
        }
        return $this->handleView($this->view($entity, Response::HTTP_OK));
    }

    public function postAction(Request $request){
        $entity = new Customer();
        $form = $this->createForm(CustomerType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setPassword(substr(md5($entity->getPassword()), 0, 16));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }
    
    public function putAction(Request $request,$id){
        if(!$entity=$this->getDoctrine()->getRepository(Customer::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $form = $this->createForm(CustomerEditType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }
    
    public function blankPasswordAction(Request $request){
        try{
            $content=json_decode($request->getContent(), true);
            if(empty($content["password"]) || empty($content["passwordRepeat"]))
                throw new Exception("Complete por favor ambos campos");
            if($content["password"]!==$content["passwordRepeat"])
                throw new Exception("Las contraseñas no son iguales");
            if(strlen($content["password"]) < 6)
                throw new Exception("La contraseña debe tener al menos 6 caracteres");
            $em = $this->getDoctrine()->getManager();
            if(!$entity=$em->getRepository(Customer::class)->find($content["id"]))
                throw new Exception("La contraseña debe tener al menos 6 caracteres");
            $entity->setPassword(substr(md5($content["password"]), 0, 16));
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
		}catch (Exception $excepcion) {
			return $this->handleView($this->view(array('message'=>$excepcion->getMessage()), Response::HTTP_BAD_REQUEST));
		}
    }

    public function deleteAction(Request $request,$id){
        if(!$entity=$this->getDoctrine()->getRepository(Customer::class)->find($id))
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