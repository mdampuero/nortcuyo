<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Price;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackEndBundle\Entity\ProductCategory;
use Inamika\BackOfficeBundle\Form\Price\PriceType;
use Inamika\BackOfficeBundle\Form\Price\PriceEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;

class PricesController extends Controller{

    //protected = array();
	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Prices:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_prices_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(Request $request){
        $entity=new Price();
        $form = $this->createForm(PriceType::class, $entity ,array('method' => 'POST'));
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $file=$entity->getFile();
                $nameOriginal=explode(".", $file->getClientOriginalName());
                if(end($nameOriginal)!=='csv'){
                    $file->addError(new FormError("Seleccione un formato de archivo válido, solo se admiten archivos CSV"));
                    return $this->render('InamikaBackOfficeBundle:Prices:form.html.twig',array(
                        'form' => $form->createView()
                    ));
                }
                if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
                    $i = 0;
                    $countUpdated=0;
                    $countCreated=0;
                    while (($data = fgetcsv($handle, null, ';')) !== FALSE) {
                        $data = array_map("utf8_encode", $data);
                        $i++;
                        if ($i<=1) continue;
                        if($product=$em->getRepository(Product::class)->findOneByCode($data[0])){
                            $countUpdated++;
                            if(isset($data[12]) && !empty($data[12]))
                                $product->setName($data[12]);
                            if(isset($data[13]) && !empty($data[13]))
                                $product->setDescription($data[13]);
                        }else{
                            $countCreated++;
                            $product= new Product();
                            $product->setCode($data[0]);
                            $product->setCategory(null);
                            if(isset($data[12]) && !empty($data[12]))
                                $product->setName($data[12]);
                            else
                                $product->setName($data[1]);
                            if(isset($data[13]) && !empty($data[13]))
                                $product->setDescription($data[13]);
                            else
                                $product->setDescription($data[1]);
                                $product->setBrand($data[11]);
                        }
                        $product->setPrice((float)str_replace(",",".",$data[6]));
                        $product->setVat((float)str_replace(",",".",$data[3]));
                        $product->setIsUpdate(true);
                        $product->setIsDelete(false);
                        $em->persist($product);
                    }
                    fclose($handle);
                    $fileName = md5(uniqid()).'.'.$file->getClientOriginalExtension();
                    $file->move("uploads/",$fileName);
                    $entity->setFile($fileName);
                    $entity->setCountUpdated($countUpdated);
                    $entity->setCountCreated($countCreated);
                    $em->persist($entity);
                    $em->flush();
                    $em->getRepository(Product::class)->setUpdate();
                }
                
                $this->addFlash('success', 'Lista cargada correctamente');
                return $this->redirectToRoute('inamika_backoffice_prices');
            }
        }
        return $this->render('InamikaBackOfficeBundle:Prices:form.html.twig',array(
            'form' => $form->createView()
        ));
    }	
}
