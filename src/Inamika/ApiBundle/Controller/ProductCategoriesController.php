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
use Inamika\BackOfficeBundle\Form\ProductCategory\ProductCategoryType;
use Inamika\BackEndBundle\Entity\ProductCategory;
use Inamika\BackEndBundle\Entity\Product;

class ProductCategoriesController extends FOSRestController
{   
    public function postAction(Request $request){
        $entity = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $entity);
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
        if(!$entity=$this->getDoctrine()->getRepository(ProductCategory::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $form = $this->createForm(ProductCategoryType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function getAction($id){
        if(!$entity=$this->getDoctrine()->getRepository(ProductCategory::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        return $this->handleView($this->view($entity));
    }

    public function indexAction(Request $request)
    {
        return $this->handleView($this->view($this->getDoctrine()->getRepository(ProductCategory::class)->getTree()));
    }
    
    public function productsAction(Request $request,$id)
    {
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        return $this->handleView($this->view(array(
            'data' => $this->getDoctrine()->getRepository(Product::class)->getByCategory($id, $limit, $offset, $sort, $direction)->getQuery()->getResult(),
            'recordsTotal' => $this->getDoctrine()->getRepository(Product::class)->total(),
            'recordsFiltered' => $this->getDoctrine()->getRepository(Product::class)->getByCategoryTotal($id, $limit, $offset),
            'offset' => $offset,
            'limit' => $limit,
        )));
    }

    public function parentsAction($id){
        if(!$entity=$this->getDoctrine()->getRepository(ProductCategory::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $parent=$this->getDoctrine()->getRepository(ProductCategory::class)->getParents($entity);
        return $this->handleView($this->view(['entity'=>$entity,'parent'=>$parent]));
    }

    public function deleteAction(Request $request,$id){
        if(!$entity=$this->getDoctrine()->getRepository(ProductCategory::class)->find($id))
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