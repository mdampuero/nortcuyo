<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\Request;
use Inamika\BackEndBundle\Form\Login\LoginType;
use Inamika\BackEndBundle\Entity\Customer;
use Inamika\BackOfficeBundle\Form\Customer\CustomerType;
use Inamika\BackOfficeBundle\Form\Customer\CustomerEditType;
use Symfony\Component\HttpFoundation\Session;

class ShopController extends Controller{

    protected $array=[];
    public function indexAction(){
        if(!$this->get('session')->get('_security_main'))
            return $this->redirectToRoute('inamika_frontend_shop_login');
        return $this->render('InamikaFrontendBundle:Shop:index.html.twig');
    }

    public function salientsAction(){
        return $this->render('InamikaFrontendBundle:Shop:salients.html.twig',array('data'=>$this->get('ApiCall')->get($this->generateUrl('api_salients',[],UrlGenerator::ABSOLUTE_URL).'?search%5Bvalue%5D=&start=0&length=100')));
    }
    
    public function offersAction(){
        return $this->render('InamikaFrontendBundle:Shop:offers.html.twig',array('data'=>$this->get('ApiCall')->get($this->generateUrl('api_offers_active',[],UrlGenerator::ABSOLUTE_URL))));
    }

    protected function getParentLabel($category){
        if(count($category["parent"])){
            $this->arrayCategory[]=$category["parent"]["text"];
            $this->getParentLabel($category["parent"]);
        }
        return $this->arrayCategory;
    }

    public function productsAction(Request $request){
        if(!$this->get('session')->get('_security_main'))
            return $this->redirectToRoute('inamika_frontend_shop_login');
        $query=urlencode($request->get('query'));
        $limit=$request->get('limit',30);
        $category=$request->get('category',null);
        $offset=($request->get('page',1)-1)*$limit;
        switch ($request->get('order')) {
            case 'name':
                $sort="sort=name&direction=ASC";
                break;
            case 'price_minor':
                $sort="sort=price&direction=ASC";
                break;
            case 'price_major':
                $sort="sort=price&direction=DESC";
                break;
            default:
                $sort="sort=&direction=".date("dH");
                break;
        }
        $categoryLabel='';
        if($category){
            $uri=$this->generateUrl('api_productCategories_products',['id'=>$category],UrlGenerator::ABSOLUTE_URL)."?search[value]={$query}&start={$offset}&length={$limit}&{$sort}";
            $category=$this->get('ApiCall')->get($this->generateUrl('api_productCategories_parents',['id'=>$category],UrlGenerator::ABSOLUTE_URL));
            $this->arrayCategory[]=$category["result"]["entity"]["name"];
            $arrayCategoryFlat=$this->getParentLabel($category["result"]);
            unset($arrayCategoryFlat[count($arrayCategoryFlat)-1]);
            $breadcrumb=array_reverse($arrayCategoryFlat);
            $categoryLabel=join(' / ',$breadcrumb);
        }else{
            $uri=$this->generateUrl('api_products',[],UrlGenerator::ABSOLUTE_URL)."?search[value]={$query}&start={$offset}&length={$limit}&{$sort}";
        }
        return $this->render('InamikaFrontendBundle:Shop:products.html.twig',array(
            'data'=>$this->get('ApiCall')->get($uri),
            'limit'=>$limit,
            'categoryLabel'=>$categoryLabel
        ));
    }

    public function viewAction(Request $request,$code){
        if(!$this->get('session')->get('_security_main'))
            return $this->redirectToRoute('inamika_frontend_shop_login');
        $result=$this->get('ApiCall')->get($this->generateUrl('api_products_get',['code'=>$code],UrlGenerator::ABSOLUTE_URL));
        if($result['code']!=200) 
            throw $this->createNotFoundException('El prodcuto no existe');
        if($request->get('isModal')==1)
            return $this->render('InamikaFrontendBundle:_modals/Shop:view.html.twig',array('data'=>$result));
        else 
            return $this->render('InamikaFrontendBundle:Shop:view.html.twig',array('data'=>$result));
    }
    
    public function relatedAction(Request $request,$code){
        return $this->render('InamikaFrontendBundle:Shop:related.html.twig',array('data'=>$this->get('ApiCall')->get($this->generateUrl('api_products_related',['code'=>$code],UrlGenerator::ABSOLUTE_URL))));
    }

    public function loginAction(){
        return $this->render('InamikaFrontendBundle:Shop:login.html.twig',array(
            'form' => $this->createForm(LoginType::class, null,array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_login_post')
            ))->createView()
        ));
    }
    
    public function logoutAction(){
        $this->get('session')->set('_security_main', null);
        return $this->redirectToRoute('inamika_frontend_shop_login');
    }
    
    public function registerAction(){
        return $this->render('InamikaFrontendBundle:Shop:register.html.twig',array(
            'form' => $this->createForm(CustomerType::class, new Customer(),array(
                'method' => 'POST',
                'action' => $this->generateUrl('api_customers_post')
            ))->createView()
        ));
    }

    public function accountAction(){
        if(!$this->get('session')->get('_security_main'))
            return $this->redirectToRoute('inamika_frontend_shop_login');
        return $this->render('InamikaFrontendBundle:Shop:account.html.twig',array(
            'form' => $this->createForm(CustomerEditType::class, $this->getDoctrine()->getRepository(Customer::class)->find($this->get('session')->get('_security_main')["customer"]->getId()),array(
                'method' => 'PUT',
                'action' => $this->generateUrl('api_customers_put',array('id'=>$this->get('session')->get('_security_main')["customer"]->getId()))
            ))->createView()
        ));
    }
    
    public function cartAction(){
        if(!$this->get('session')->get('_security_main'))
            return $this->redirectToRoute('inamika_frontend_shop_login');
        return $this->render('InamikaFrontendBundle:Shop:cart.html.twig');
    }
    
    public function ordersAction(){
        if(!$this->get('session')->get('_security_main'))
            return $this->redirectToRoute('inamika_frontend_shop_login');
        return $this->render('InamikaFrontendBundle:Shop:orders.html.twig',array('data'=>$this->get('ApiCall')->get($this->generateUrl('api_customers_orders',['id'=>$this->get('session')->get('_security_main')["customer"]->getId()],UrlGenerator::ABSOLUTE_URL))));
    }
    
    public function preformattedAction(){
        if(!$this->get('session')->get('_security_main'))
            return $this->redirectToRoute('inamika_frontend_shop_login');
        return $this->render('InamikaFrontendBundle:Shop:preformatted.html.twig');
    }
}
