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

class EventoType extends AbstractType
{
    public function __construct(private ManagerRegistry $doctrine)
    {
        
    } 

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('fecha')
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
