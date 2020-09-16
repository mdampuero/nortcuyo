<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2019.
//  Copyright © 2019 Inamika S.A. All rights reserved.
//

namespace Inamika\BackOfficeBundle\Form\Offer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OfferType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',TextType::class,array('label'=>'Título','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','maxlength'=>32,'placeholder'=>'Nombre')))
        ->add('color',TextType::class,array('label'=>'Color de tarjeta','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','maxlength'=>7,'placeholder'=>'#f5f6f9')))
        ->add('ribbon',TextType::class,array('label'=>'Ribbon','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control','maxlength'=>25,'placeholder'=>'Ej: 20% de descuento')))
        ->add('dateFrom',DateType::class,array(
            'label'=>'Val. desde',
            'widget' => 'single_text',
            'label_attr'=>array(
                'class'=>'control-label'
            ),
            'attr'=>array(
                'class'=>'form-control',
                'placeholder'=>'Nombre'
            )
        ))
        ->add('type',ChoiceType::class, array('label'=>'Tipo de oferta','label_attr'=>array('class'=>'control-label'),'attr'=>array('class'=>'form-control'),'choices' => array(
            'Por producto' => 'BY_PRODUCT',
            'Por categoría' => 'BY_CATEGORY'
            )))
        ->add('dateTo',DateType::class,array(
            'label'=>'Val. hasta',
            'widget' => 'single_text',
            'label_attr'=>array(
                'class'=>'control-label'
            ),
            'attr'=>array(
                'class'=>'form-control',
                'placeholder'=>'Nombre'
            )
        ))
        ->add('pictureBase64',HiddenType::class,array("mapped" => false))
        ->add('picture', FileType::class, array(
            'label'=>'PHOTO',
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
        ->add('product',HiddenType::class,array("mapped" => false))
        ->add('category',HiddenType::class,array("mapped" => false))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'=>false,
            'allow_extra_fields'=>true,
            'data_class' => 'Inamika\BackEndBundle\Entity\Offer'
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
