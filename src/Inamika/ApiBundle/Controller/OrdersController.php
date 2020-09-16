<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Orders;
use Inamika\BackEndBundle\Entity\OrdersStatus;
use Inamika\BackEndBundle\Entity\Log;
use Inamika\BackEndBundle\Entity\OrdersItem;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackOfficeBundle\Form\Order\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

class OrdersController extends FOSRestController
{
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', array());
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        return $this->handleView($this->view(array(
            'data' => $this->getDoctrine()->getRepository(Orders::class)->search($search["value"], $limit, $offset, $sort, $direction)->getQuery()->getResult(),
            'recordsTotal' => $this->getDoctrine()->getRepository(Orders::class)->total(),
            'recordsFiltered' => $this->getDoctrine()->getRepository(Orders::class)->searchTotal($search["value"], $limit, $offset),
            'offset' => $offset,
            'limit' => $limit,
        )));
    }

    public function changeOrderAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        if(!$entity=$em->getRepository(Orders::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));
        $content=json_decode($request->getContent(), true);
        if(!$entityStatus=$em->getRepository(OrdersStatus::class)->find($content["status"]))
            return $this->handleView($this->view(array('message'=>'Ocurrió un error inesperado'), Response::HTTP_NOT_FOUND));
        if($entity->getStatus()==$entityStatus)
            return $this->handleView($this->view(array('message'=>'Debe seleccionar un estado diferente'), Response::HTTP_BAD_REQUEST));
        $oldStatusName=$entity->getStatus()->getName();
        $this->get('ApiCall')->post($this->generateUrl('api_logs_post',[],UrlGenerator::ABSOLUTE_URL),
        [
            "title"=>"De '".$oldStatusName."' a '".$entityStatus->getName()."'",
            "description"=> $content["comment"],
            "resource"=>"order_number_".$entity->getId(),
            "icon"=>"mdi mdi-alert-circle",
            "status"=>$entityStatus->getColor(),
            "user"=>$content["user"]
        ]
        );
        $entity->setStatus($entityStatus);
        $em->persist($entity);
        $em->flush();
        if($content["notify"]){
            if($entity->getCustomerId()->getToken()){
                /** Generar notificacion */
                $result=$this->get('FirebaseNotifications')->push(
                    [
                        "notification"=>array("title"=>"Cambios en tu pedido #".$entity->getId(),"body"=>"Tu pedido cambió de estado '".$oldStatusName."' a '".$entityStatus->getName()."'"),
                        "to"=> $entity->getCustomerId()->getToken()
                    ]);
                /** Fin Generar notificacion */
            }
            /** Enviar email */
            $settings = $this->container->get('setting');
            $message = (new \Swift_Message($this->get('setting')->getData()->getTitle().' - Cambios en tu pedido #'.$entity->getId()))
            ->setFrom(array($this->getParameter('mailer_user')=>$this->get('setting')->getData()->getTitle()))
            ->setTo($entity->getCustomerId()->getEmail())
            ->setBody($this->renderView('InamikaBackOfficeBundle:Emails:Orders/changeStatus.html.twig', array('entity' => $entity,'status'=>$oldStatusName)),'text/html');
            $this->get('mailer')->send($message);
            /** Fin enviar email */
        }

        return $this->handleView($this->view($entityStatus, Response::HTTP_OK));
    }

    public function postAction(Request $request){
        $entity = new Orders();
        $form = $this->createForm(OrderType::class, $entity);
        $content=json_decode($request->getContent(), true);
        $form->submit($content);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setStatus($this->getDoctrine()->getRepository(OrdersStatus::class)->findOneByIsDefault(true));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $items=$content['items'];
            foreach ($items as $key => $item) {
                $ordersItem=new OrdersItem();
                $ordersItem->setProductId($em->getRepository(Product::class)->find($item["id"]));
                $ordersItem->setOrderId($entity);
                $ordersItem->setCode($item["code"]);
                $ordersItem->setAmount($item["amount"]);
                $ordersItem->setDescription($item["name"]);
                $ordersItem->setPrice($item["price"]);
                $ordersItem->setSubtotal($item["subtotal"]);
                $ordersItem->setSubtotalVat($item["subtotalVat"]);
                $em->persist($ordersItem);
            }
            $em->flush();
            $this->get('ApiCall')->post($this->generateUrl('api_logs_post',[],UrlGenerator::ABSOLUTE_URL),
                [
                    "title"=>"Nuevo pedido",
                    "description"=> "Ingreso de pedido desde '".$entity->getChannel()."'",
                    "resource"=>"order_number_".$entity->getId(),
                    "icon"=>"mdi mdi-login-variant",
                    "status"=>"success",
                    "user"=>null
                ]
            );
            return $this->handleView($this->view($entity, Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    public function getAction($id){
        if (!$entity = $this->getDoctrine()->getRepository(Orders::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));

        return $this->handleView($this->view($entity));
    }
    
    public function logsAction($id){
        $entities = $this->getDoctrine()->getRepository(Log::class)->findByResource("order_number_${id}",array('createdAt'=>'DESC'));
        return $this->handleView($this->view($entities));
    }
    
    public function emailAction($id){
        if (!$entity = $this->getDoctrine()->getRepository(Orders::class)->find($id))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));

        //SEND EMAIL
        $settings = $this->container->get('setting');
        $message = (new \Swift_Message($this->get('setting')->getData()->getTitle().' - Pedido' ))
        ->setFrom(array($this->getParameter('mailer_user')=>$this->get('setting')->getData()->getTitle()))
        ->setTo($this->get('setting')->getData()->getOrdersEmail())
        ->setBody($this->renderView('InamikaBackOfficeBundle:Emails:Orders/sending.html.twig', array('entity' => $entity)),'text/html')
        // ->setBcc([
        //     $settings->getData()->getBillEmail()
        // ])
        ;
        $this->get('mailer')->send($message);
        return $this->handleView($this->view($entity));
    }

    public function deleteAction(Request $request, $id)
    {
        if (!$entity = $this->getDoctrine()->getRepository(Orders::class)->find($id))
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
