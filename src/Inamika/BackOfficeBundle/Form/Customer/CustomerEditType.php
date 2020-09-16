<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Form\Customer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CustomerEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',TextType::class,array('label'=>'Nombre','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'Nombre')))
        ->add('email',TextType::class,array('label'=>'Email','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'Email')))
        ->add('phone',TextType::class,array('label'=>'Teléfono','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'Teléfono')))
        ->add('address',TextType::class,array('label'=>'Dirección','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'Dirección')))
        ->add('city',TextType::class,array('label'=>'Ciudad','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'Ciudad')))
        ->add('provence',TextType::class,array('label'=>'Provincia','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'Provincia')))
        ->add('document',TextType::class,array('label'=>'DNI/CUIT/CUIL','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'DNI/CUIT/CUIL')))
        ->add('observations',TextareaType::class,array('label'=>'DESCRIPTION','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'DESCRIPTION')))
        ->add('isSeller',ChoiceType::class, array('label'=>'Es vendedor','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control'),'choices' => array(
            'NO' => 0,
            'SI' => 1
            )))
        ->add('customerCategory', EntityType::class, array(
            'label'=>'Categoría',
            'label_attr'=>array('class'=>'control-label'),
            'class' => 'InamikaBackEndBundle:CustomerCategory',
            'choice_label' => 'name',
            'attr'=>array('class'=>'form-control'),
            'placeholder' => '--Seleccione una opción--',
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('e');
                $choices=$qb->where("e.isDelete=:isDelete")->setParameter('isDelete',false)
                    ->orderBy('e.name', 'ASC');
                return $choices;
            }
        ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'=>false,
            'allow_extra_fields'=>true,
            'data_class' => 'Inamika\BackEndBundle\Entity\Customer'
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
