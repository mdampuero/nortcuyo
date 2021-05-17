<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Offer;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackEndBundle\Entity\ProductCategory;
use Inamika\BackOfficeBundle\Form\Offer\OfferType;
use Inamika\BackOfficeBundle\Form\Offer\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;

class OffersController extends DefaultController
{
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', array());
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        $results=$this->getDoctrine()->getRepository(Offer::class)->search($search["value"], $limit, $offset, $sort, $direction)->getQuery()->getResult();
        $now=new \DateTime();
        $yesterday=new \DateTime();
        foreach ($results as $key => $result) {
            if(($result->getDateFrom() <= $now) && ($result->getDateTo() >= $yesterday->modify('-1 day')))
                $results[$key]->setIsActive(true);
        }
        return $this->handleView($this->view(array(
            'data' => $results,
            'recordsTotal' => $this->getDoctrine()->getRepository(Offer::class)->total(),
            'recordsFiltered' => $this->getDoctrine()->getRepository(Offer::class)->searchTotal($search["value"], $limit, $offset),
            'offset' => $offset,
            'limit' => $limit,
        )));
    }
    
    public function activeAction(Request $request)
    {
        $results=[];
        $now=new \DateTime();
        $yesterday=new \DateTime();
        foreach ($this->getDoctrine()->getRepository(Offer::class)->getAll()->orderBy('RAND()')->getQuery()->getResult() as $key => $result) {
            if(($result->getDateFrom() <= $now) && ($result->getDateTo() >= $yesterday->modify('-1 day')))
                $results[]=$result;
        }
        return $this->handleView($this->view(array(
            'data' => $results
        )));
    }

    public function getAction($code){
        if (!$entity = $this->getDoctrine()->getRepository(Offer::class)->findOneByCode($code))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));

        return $this->handleView($this->view($entity));
    }

    public function postAction(Request $request){
        $entity = new Offer();
        $form = $this->createForm(OfferType::class, $entity);
        $content=json_decode($request->getContent(), true);
        $form->submit($content);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('pictureBase64')->getData())
                $entity->setPicture($this->base64ToFile($form->get('pictureBase64')->getData(),"uploads/"));
            if($entity->getType()=='BY_CATEGORY')
                if(!$content["category"])
                    return $this->handleView($this->view(array(
                        'form'=>array(
                            'children'=>array(
                                'category'=>array(
                                    'errors'=>["Debe seleccionar una categoría"]
                                )
                            )
                        )
                    ), Response::HTTP_BAD_REQUEST));
                else{
                    $entity->setProduct(null);
                    $entity->setCategory($this->getDoctrine()->getRepository(ProductCategory::class)->find($content["category"]));
                }
            if($entity->getType()=='BY_PRODUCT')
                if(!$content["product"])
                    return $this->handleView($this->view(array(
                        'form'=>array(
                            'children'=>array(
                                'product'=>array(
                                    'errors'=>["Debe seleccionar un producto"]
                                )
                            )
                        )
                    ), Response::HTTP_BAD_REQUEST));
                else{
                    $entity->setCategory(null);
                    $entity->setProduct($this->getDoctrine()->getRepository(Product::class)->find($content["product"]));
                }
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function putAction(Request $request, $id)
    {
        if (!$entity = $this->getDoctrine()->getRepository(Offer::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $pictureOld=$entity->getPicture();
        $form = $this->createForm(OfferType::class, $entity);
        $content=json_decode($request->getContent(), true);
        $form->submit($content);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('pictureBase64')->getData())
                $entity->setPicture($this->base64ToFile($form->get('pictureBase64')->getData(),"uploads/"));
            else
                $entity->setPicture($pictureOld);
            if($entity->getType()=='BY_CATEGORY')
                if(!$content["category"])
                    return $this->handleView($this->view(array(
                        'form'=>array(
                            'children'=>array(
                                'category'=>array(
                                    'errors'=>["Debe seleccionar una categoría"]
                                )
                            )
                        )
                    ), Response::HTTP_BAD_REQUEST));
                else{
                    $entity->setProduct(null);
                    $entity->setCategory($this->getDoctrine()->getRepository(ProductCategory::class)->find($content["category"]));
                }
            if($entity->getType()=='BY_PRODUCT')
                if(!$content["product"])
                    return $this->handleView($this->view(array(
                        'form'=>array(
                            'children'=>array(
                                'product'=>array(
                                    'errors'=>["Debe seleccionar un producto"]
                                )
                            )
                        )
                    ), Response::HTTP_BAD_REQUEST));
                else{
                    $entity->setCategory(null);
                    $entity->setProduct($this->getDoctrine()->getRepository(Product::class)->find($content["product"]));
                }
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function deleteAction(Request $request, $id)
    {
        if (!$entity = $this->getDoctrine()->getRepository(Offer::class)->find($id))
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
