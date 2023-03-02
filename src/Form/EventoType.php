<?php

namespace App\Form;

use App\Entity\Evento;
use App\Entity\Juego;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventoType extends AbstractType
{
    public function __construct(private ManagerRegistry $doctrine)
    {
        
    } 

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', null, [

                    'required'=> false, 
                    
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Porfavor, inserte un nombre para el evento',
                        ]),
                        new Length([
                            'min' => 1,
                            'minMessage' => 'Tu nombre debe de tener minimo {{ limit }} caracteres',
                            'max' => 50,
                        ]),
                ]
            ])

            ->add('fecha', null, [

                'required'=> false, 
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Porfavor, inserte una fecha para el evento',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'La fecha debe de tener minimo {{ limit }} caracteres (YYYY-MM-DD)',
                        'max' => 10,
                    ]),
                ]
            ])
            ->add('juego',  EntityType::class, [
                'class' => Juego::class, 
                'choices' => $this->doctrine->getRepository(Juego::class)->findAll(),
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-primary w-100 py-3']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evento::class,
        ]);
    }
}
