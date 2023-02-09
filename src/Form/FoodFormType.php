<?php

namespace App\Form;

use App\Entity\Food;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FoodName'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('Restaurant'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('FoodCategory'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('ServingSize'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('ServingSizeUnit'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('EnergyAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('EnergyUnit'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('FatAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('FatUnit'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('CarbAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('CarbUnit'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('ProteinAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('ProteinUnit'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('Quantity'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('PotassiumAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('PotassiumUnit'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('FiberAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('FiberUnit'
                ,TextType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'text'
                    )
                ]
            )
            ->add('Date'
            ,DateType::class,[
                'attr'=>array(
                    'class'=>'form-control',
                )
            ])
            ->add('TotalEnergyAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('TotalFiberAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('TotalPotassiumAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('TotalFatAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('TotalCarbAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
            ->add('TotalProteinAmount'
                ,NumberType::class,[
                    'attr'=>array(
                        'class'=>'form-control',
                        'type'=>'number'
                    )
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Food::class,
        ]);
    }
}
