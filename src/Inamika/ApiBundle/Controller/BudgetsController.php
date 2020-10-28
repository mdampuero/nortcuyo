<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackEndBundle\Entity\Currency;
use Inamika\BackOfficeBundle\Form\Budget\BudgetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class BudgetsController extends FOSRestController
{

    public function postAction(Request $request){
        
        $form = $this->createForm(BudgetType::class);
        $content=json_decode($request->getContent(), true);
        $form->submit($content);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $items=$content['items'];
            $itemsArray=[];

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $phpExcelObject->getProperties()->setCreator("Nortcuyo")
                ->setLastModifiedBy("Nortcuyo")
                ->setTitle("Proforma")
                ->setSubject("Proforma")
                ->setDescription("Proforma Nortcuyo");
            $data=$this->getDoctrine()->getRepository(Product::class)->getAll()
            ->orderBy('e.code','DESC')
            ->getQuery()->getResult();
            
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A1','Item')
                ->setCellValue('B1','Código')
                ->setCellValue('C1','Descripción')
                ->setCellValue('D1','P.U.')
                ->setCellValue('E1','Cantidad')
                ->setCellValue('F1','Subtotal');
            $row=2;
            foreach ($items as $key => $item) {
                $product=$em->getRepository(Product::class)->find($item["id"]);
                $items[$key]["product"]=$product;
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('A'.$row, (String)($key+1))
                    ->setCellValue('B'.$row, $product->getCode())
                    ->setCellValue('C'.$row, $product->getName())
                    ->setCellValue('D'.$row, $product->getCurrency()->getSymbol()." ".(String)round($item["subtotal"]/$item["amount"],2))
                    ->setCellValue('E'.$row, (String)round($item["amount"],2))
                    ->setCellValue('F'.$row, $product->getCurrency()->getSymbol()." ".(String)round($item["subtotal"],2));
                $row++;
            }
            
            $this->calcTotal($items,$phpExcelObject,$row);

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
            
            $filePath='uploads/or/'.'Nortcuyo_'.date('d_m_Y_H_i_s').'.xlsx';
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
            $writer->save($filePath);
        
            return $this->handleView($this->view(["budget"=>["path"=>$baseurl."/".$filePath]], Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    private function calcTotal($items,$phpExcelObject,$row){
        $em = $this->getDoctrine()->getManager();
        $currencies=$em->getRepository(Currency::class)->getAll()->getQuery()->getResult();
        $totals=[];
        foreach ($currencies as $key => $currency) {
            $totals[$currency->getSymbol()]=array('total'=>0,'totalVat'=>0,'currency'=>$currency);
            foreach ($items as $key => $item) {
                if($item["product"]->getCurrency()->getId()==$currency->getId()){
                    $totals[$currency->getSymbol()]["total"]+=$item["subtotal"];
                    $totals[$currency->getSymbol()]["totalVat"]+=$item["subtotalVat"];
                }
            }
        }
        foreach ($totals as $key => $total) {
            $row++;
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('E'.$row, "Total S/I ".$key)
                ->setCellValue('F'.$row, (String)round($total["total"],2));
            $row++;
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('E'.$row, "IVA ".$key)
                ->setCellValue('F'.$row, (String)round($total["totalVat"]-$total["total"],2));
            $row++;
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('E'.$row, "Total ".$key)
                ->setCellValue('F'.$row, (String)round($total["totalVat"],2));
            $row++;
        }
        return true;
    }

}
