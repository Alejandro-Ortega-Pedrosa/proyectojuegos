<?php

namespace App\Form;

use App\Entity\Juego;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JuegoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre',null, ['attr' => ['class' => 'form-control bg-transparent']])
            ->add('width',null, ['attr' => ['class' => 'form-control bg-transparent']])
            ->add('height',null, ['attr' => ['class' => 'form-control bg-transparent']])
            ->add('numminimo',null, ['attr' => ['class' => 'form-control bg-transparent']])
            ->add('nummaximo',null, ['attr' => ['class' => 'form-control bg-transparent']])
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