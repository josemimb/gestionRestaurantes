<?php

namespace App\Form;

use App\Entity\Mesa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Restaurante;
use App\Form\RestauranteType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class MesaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Nombre" es obligatorio',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 50,
                        'minMessage' => 'El campo "Nombre" debe tener al menos {{ limit }} caracteres.',
                        'maxMessage' => 'El campo "Nombre" no puede tener más de {{ limit }} caracteres.',
                    ]),
                ],
            ])
            ->add('numeroPersonas', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "numero de personas" es obligatorio.',
                    ]),
                    
                    new Regex([
                        'pattern' => '/^\d{1,10}$/',
                        'message' => 'El campo "contacto" debe contener números entre 1 y 10 caracteres.',
                    ]),
                ],
            ])
            ->add('restaurante', EntityType::class, [
                'class' => 'App\Entity\Restaurante',
                'choice_label' => 'nombre',
                'label' => false,
                'attr' => [
                    'class' => 'd-none'
                ],
                //'disabled' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mesa::class,
        ]);
    }
}
