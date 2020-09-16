<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Form\OrderItem;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrderItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('product_id',TextType::class,array('label'=>'Producto','constraints' => array(new NotBlank())))
        ->add('price',TextType::class,array('label'=>'Precio','constraints' => array(new NotBlank())))
        ->add('description',TextType::class,array('label'=>'Descripción','constraints' => array(new NotBlank())))
        ->add('code',TextType::class,array('label'=>'Código','constraints' => array(new NotBlank())))
        ->add('amount',TextType::class,array('label'=>'Cantidad','constraints' => array(new NotBlank())))
    ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'=>false,
            'allow_extra_fields'=>true,
            'data_class' => 'Inamika\BackEndBundle\Entity\OrdersItem'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }


}
