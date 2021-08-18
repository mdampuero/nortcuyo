<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Controller;

use Inamika\BackEndBundle\Entity\Preformatted;
use Inamika\BackEndBundle\Entity\PreformattedItem;
use Inamika\BackEndBundle\Entity\Product;
use Inamika\BackOfficeBundle\Form\Preformatted\PreformattedType;
use Inamika\BackOfficeBundle\Form\Preformatted\PreformattedEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;

class PreformattedsController extends Controller{

	public function indexAction(){
		return $this->render('InamikaBackOfficeBundle:Preformatteds:index.html.twig',array(
            'formDelete'=>$this->createFormBuilder()
            ->setAction($this->generateUrl('api_preformatted_items_delete', array('id' => ':ENTITY_ID')))
            ->setMethod('DELETE')
            ->getForm()->createView()
        ));
    }

    public function addAction(Request $request){
        $entity=new Preformatted();
        $form = $this->createForm(PreformattedType::class, $entity ,array('method' => 'POST'));
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $file=$entity->getFile();
                $nameOriginal=explode(".", $file->getClientOriginalName());
                if(end($nameOriginal)!=='csv'){
                    $file->addError(new FormError("Seleccione un formato de archivo válido, solo se admiten archivos CSV"));
                    return $this->render('InamikaBackOfficeBundle:Preformatteds:form.html.twig',array(
                        'form' => $form->createView()
                    ));
                }
                if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
                    $i = 0;
                    $countCreated=0;
                    while (($data = fgetcsv($handle, null, ';')) !== FALSE) {
                        $data = array_map("utf8_encode", $data);
                        $i++;
                        if($i>1 && $product=$em->getRepository(Product::class)->findOneBy(['code'=>$data[0],'isDelete'=>false])){
                            $countCreated++;
                            $item=new PreformattedItem();
                            $item->setProduct($product);
                            $item->setPosition($countCreated);
                            $em->persist($item);
                        }
                    }
                    fclose($handle);
                    $fileName = md5(uniqid()).'.'.$file->getClientOriginalExtension();
                    $file->move("uploads/",$fileName);
                    $entity->setFile($fileName);
                    $entity->setCountCreated($countCreated);
                    $em->persist($entity);
                    /** Elimina las anteriores */
                    $em->getRepository(PreformattedItem::class)->removeAll();
                    /** Escribe en base */
                    $em->flush();
                }
                $this->addFlash('success', 'Lista preformateada cargada correctamente');
                return $this->redirectToRoute('inamika_backoffice_preformatteds');
            }
        }
        return $this->render('InamikaBackOfficeBundle:Preformatteds:form.html.twig',array(
            'form' => $form->createView()
        ));
    }	
}
