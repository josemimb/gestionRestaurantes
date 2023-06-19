<?php

namespace App\Form;

use App\Entity\Opinion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('valoracion', HiddenType::class)
            ->add('comentario', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo comentario es obligatorio.',
                    ]),
                ],
            ])
            ->add('respuesta', TextareaType::class, [
                'required' => false,
                // 'constraints' => [
                //     new NotBlank([
                //         'message' => 'El campo respuesta es obligatorio.',
                //     ]),
                // ],
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'nombre',
                'label' => false,
                'disabled' => true,
                'attr' => [
                    'class' => 'd-none'
                ],
            ])
            ->add('restaurante', EntityType::class, [
                'class' => 'App\Entity\Restaurante',
                'label' => false,
                'attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add('fecha', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'attr' => [
                    'class' => 'd-none'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opinion::class,
        ]);
    }
}
