<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Price;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackOfficeBundle\Form\Price\PriceType;
use Inamika\BackOfficeBundle\Form\Price\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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

    public function downloadAction(){
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Nortcuyo")
            ->setLastModifiedBy("Nortcuyo")
            ->setTitle("Lista de precios Nortcuyo")
            ->setSubject("Lista de precios Nortcuyo")
            ->setDescription("Este documento contiene la lista de precios de nortcuyo");
        $data=$this->getDoctrine()->getRepository(Product::class)->getAll()
        ->orderBy('e.code','DESC')
        ->getQuery()->getResult();
        
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1','Código')
            ->setCellValue('B1','Descripción')
            ->setCellValue('C1','Moneda')
            ->setCellValue('D1','P.Lista S/I')
            ->setCellValue('E1','P.Lista C/I')
            ->setCellValue('F1','IVA');
        $row=2;
        foreach ($data as $key => $d) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$row, $d->getCode())
                ->setCellValue('B'.$row, $d->getName())
                ->setCellValue('C'.$row, $d->getCurrency()->getSymbol())
                ->setCellValue('D'.$row, (String)round($d->getPrice(),2))
                ->setCellValue('E'.$row, (String)round(($d->getPrice()*$d->getVat()),2))
                ->setCellValue('F'.$row, (String)$d->getVat());
            $row++;
        }
        foreach(range('A','F') as $columnID) {
            $phpExcelObject->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $phpExcelObject->getActiveSheet()->setTitle('Lista de precios');
        // Define el indice de página al número 1, para abrir esa página al abrir el archivo
        $phpExcelObject->setActiveSheetIndex(0);

        // Crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // Envia la respuesta del controlador
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // Agrega los headers requeridos
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'NortcuyoListaDePrecio'.date('d_m_Y_H_i').'.xlsx'
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
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
