<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('price',TextType::class,array('label'=>'Precio','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control')))
        ->add('vat',TextType::class,array('label'=>'IVA','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control')))
        ->add('code',TextType::class,array('label'=>'Código','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control')))
        ->add('barcode',TextType::class,array('label'=>'EAN','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control')))
        ->add('name',TextType::class,array('label'=>'Nombre','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control')))
        ->add('unit',NumberType::class,array('label'=>'Unidad de compra','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','placeholder'=>'Ej: 1')))
        ->add('description',TextareaType::class,array('label'=>'Descripción','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','rows'=>5,'placeholder'=>'Describa en detalle el producto')))
        ->add('tags',TextareaType::class,array('label'=>'Tags','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','rows'=>5,'placeholder'=>'Escriba palabras claves que ayudan a la búsqueda')))
        ->add('category',HiddenType::class,array("mapped" => false))
        ->add('currency', EntityType::class, array(
            'label'=>'Moneda',
            'label_attr'=>array('class'=>'control-label'),
            'class' => 'InamikaBackEndBundle:Currency',
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
        /* File picture */
        ->add('pictureBase64',HiddenType::class,array("mapped" => false))
        ->add('pictureRemove',HiddenType::class,array("mapped" => false))
        ->add('picture', FileType::class, array(
            'label'=>'Foto principal',
            'data_class' => null,
            'label_attr'=>array('class'=>'control-label'),
            'attr'=>array(
                'onchange'=>'encodeImageFileAsURL(this)',
                'class'=>'dropify',
                'data-height'=>'300',
                'data-max-file-size'=>'2M',
                'data-allowed-file-extensions'=>'png jpg jpeg gif'
                )
            )
        )
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'=>false,
            'allow_extra_fields'=>true,
            'data_class' => 'Inamika\BackEndBundle\Entity\Product'
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
