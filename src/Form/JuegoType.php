<?php

namespace App\Form;

use App\Entity\Juego;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class JuegoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', null, [

                'required'=> false, 
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Porfavor, inserte un nombre para el juego',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'El nombre debe de tener minimo {{ limit }} caracteres',
                        'max' => 100,
                    ]),
                ]
            ])

            ->add('width', null, [

                'required'=> false, 
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Porfavor, inserte un width para el juego',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'El width debe de tener minimo {{ limit }} caracteres',
                        'max' => 10,
                    ]),
                ]
            ])

            ->add('height', null, [

                'required'=> false, 
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Porfavor, inserte un height para el juego',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'El height debe de tener minimo {{ limit }} caracteres',
                        'max' => 10,
                    ]),
                ]
            ])

            ->add('numminimo', null, [

                'required'=> false, 
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Porfavor, inserte un número mínimo de jugadores',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'El número mínimo debe de tener minimo {{ limit }} caracteres',
                        'max' => 10,
                    ]),
                ]
            ])

            ->add('nummaximo', null, [

                'required'=> false, 
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Porfavor, inserte un número máximo de jugadores',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'El número máximo debe de tener minimo {{ limit }} caracteres',
                        'max' => 10,
                    ]),
                ]
            ])

            ->add('foto', FileType::class, [
                'label' => 'Foto (.jpg .png)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-primary w-100 py-3']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
        ]);
    }
}