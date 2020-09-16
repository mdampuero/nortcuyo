<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackEndBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Inamika\BackEndBundle\Entity\Product;

class ProductsFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface{

    private $container;

    public function setContainer(ContainerInterface $container = null){
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager){
        for ($i=1; $i<=10000; $i++){
            $product = new Product();
            $product->setCode("NBH".str_pad($i, 7, "0", STR_PAD_LEFT));
            $product->setName("Producto - ".$i);
            $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi quis vehicula felis, sit amet maximus purus. Ut consectetur, ipsum sed iaculis sodales, sem urna viverra felis, ut venenatis quam elit in elit. Donec viverra, nisi non placerat rhoncus, justo odio rhoncus nunc, sit amet rhoncus ipsum ante ut elit. Nunc consequat ac odio vitae auctor. Maecenas erat nibh, aliquam in blandit eget, luctus nec odio. Nunc posuere ornare libero non venenatis. Vestibulum a arcu nec sapien porttitor gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus semper, mauris fermentum eleifend molestie, tortor massa semper orci, a imperdiet sapien eros congue dui. Mauris convallis lacus at magna cursus tristique. In posuere arcu at interdum posuere. Aliquam enim sapien, malesuada non lectus ut, tincidunt interdum dolor. Phasellus id libero at ligula iaculis viverra in vel orci. Praesent condimentum, sapien a pharetra tempus, neque velit viverra enim, at volutpat lectus risus a turpis.");
            $product->setPrice($i);
            $manager->persist($product);
        }
        $manager->flush();
    }
    
    public function getOrder(){
        return 2;
    }
}
