<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', null, [

            'required'=> false, 
            
            'constraints' => [
                new NotBlank([
                    'message' => 'Porfavor, inserte un email',
                ]),
                new Length([
                    'min' => 10,
                    'minMessage' => 'Tu email debe de tener minimo {{ limit }} caracteres',
                    'max' => 250,
                ]),
            ]
        ])
        
        ->add('nombre', null, [

            'required'=> false, 
            
            'constraints' => [
                new NotBlank([
                    'message' => 'Porfavor, inserte un nombre',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Tu nombre debe de tener minimo {{ limit }} caracteres',
                    'max' => 20,
                ]),
            ]
        ])

        ->add('apellidos', null, [

            'required'=> false, 
            
            'constraints' => [
                new NotBlank([
                    'message' => 'Porfavor, inserte unos apellidos',
                ]),
                new Length([
                    'min' => 4,
                    'minMessage' => 'Tus apellidos deben de tener minimo {{ limit }} caracteres',
                    'max' => 30,
                ]),
            ]
        ])

        ->add('password', PasswordType::class, [
            
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Porfavor, inserte una contraseña',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Tu contraseña debe de tener minimo {{ limit }} caracteres',
                    'max' => 4096,
                ]),
            ],
        ])

        ->add('id_telegram')
            
        ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-primary w-100 py-3']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}