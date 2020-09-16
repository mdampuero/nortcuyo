<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Price;
use Inamika\BackOfficeBundle\Form\Price\PriceType;
use Inamika\BackOfficeBundle\Form\Price\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Config\Definition\Exception\Exception;


class PricesController extends DefaultController
{
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', array());
        $offset = $request->query->get('start', 0);
        $limit = $request->query->get('length', 30);
        $sort = $request->query->get('sort', null);
        $direction = $request->query->get('direction', null);
        $results=$this->getDoctrine()->getRepository(Price::class)->search($search["value"], $limit, $offset, $sort, $direction)->getQuery()->getResult();
        return $this->handleView($this->view(array(
            'data' => $results,
            'recordsTotal' => $this->getDoctrine()->getRepository(Price::class)->total(),
            'recordsFiltered' => $this->getDoctrine()->getRepository(Price::class)->searchTotal($search["value"], $limit, $offset),
            'offset' => $offset,
            'limit' => $limit,
        )));
    }

    public function getAction($code){
        if (!$entity = $this->getDoctrine()->getRepository(Price::class)->findOneByCode($code))
            return $this->handleView($this->view(null, Response::HTTP_NOT_FOUND));

        return $this->handleView($this->view($entity));
    }

    public function deleteAction(Request $request, $id)
    {
        if (!$entity = $this->getDoctrine()->getRepository(Price::class)->find($id))
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
