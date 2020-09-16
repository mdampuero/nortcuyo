<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Form\Order;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Inamika\BackOfficeBundle\Form\OrderItem\OrderItemType;

class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('channel',TextType::class,array('label'=>'Canal'))
        ->add('total',NumberType::class,array('label'=>'Total'))
        ->add('totalVat',NumberType::class,array('label'=>'Total IVA'))
        ->add('observations',TextType::class,array('label'=>'Observaciones'))
        ->add('transport',TextType::class,array('label'=>'Transporte'))
        ->add('customerName',TextType::class,array('label'=>'Cliente'))
        ->add('customerId', EntityType::class, array(
            'label'=>'BANKS',
            'class' => 'InamikaBackEndBundle:Customer',
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('e');
                $choices=$qb->where("e.isDelete=:isDelete")->setParameter('isDelete',false)
                    ->orderBy('e.name', 'ASC');
                return $choices;
            }
        ))
     //     ->add('items', CollectionType::class, array(
    //         'entry_type'   => OrderItemType::class,
    //         'allow_add' => true,
    //     )
    // )
    ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'=>false,
            'allow_extra_fields'=>true,
            'data_class' => 'Inamika\BackEndBundle\Entity\Orders'
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
