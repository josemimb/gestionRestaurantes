<?php

namespace App\Form;

use App\Entity\Restaurante;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Choice;


class RestauranteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Nombre" es obligatorio.',
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 50,
                        'minMessage' => 'El campo "Nombre" debe tener al menos {{ limit }} caracteres.',
                        'maxMessage' => 'El campo "Nombre" no puede tener más de {{ limit }} caracteres.',
                    ]),
                    new Regex([
                        'pattern' => '/^[^0-9]+$/',
                        'message' => 'El campo "Nombre" no puede contener números.',
                    ]),
                ],
            ])
            ->add('direccion', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'ej. Nombre de la Calle, Número, Código Postal, Ciudad'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Dirección" es obligatorio.',
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 50,
                        'minMessage' => 'El campo "Dirección" debe tener al menos {{ limit }} caracteres.',
                        'maxMessage' => 'El campo "Dirección" no puede tener más de {{ limit }} caracteres.',
                    ]),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Imagen del restaurante" es obligatorio.',
                    ]),
                ],
            ])
            ->add('imageFileCarta', VichImageType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Imagen de la carta" es obligatorio.',
                    ]),
                ],
            ])
            ->add('contacto', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Contacto" es obligatorio.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{9,11}$/',
                        'message' => 'El campo "contacto" debe contener números entre 9 y 11 caracteres.',
                    ]),
                ],
            ])
            ->add('horario', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Horario" es obligatorio.',
                    ]),
                ],
            ])
            ->add('descripcion', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'El campo "Descripcion" es obligatorio.',
                    ]),
                ],
            ])
            ->add('user', EntityType::class, [
                'required' => false,
                'class' => User::class, // Reemplaza "User" con tu clase de entidad de usuario
                // 'choice_label' => 'username', // Reemplaza "username" con el campo que quieres mostrar en el desplegable
                'placeholder' => 'Seleccione un usuario', // Opción por defecto
                'constraints' => [
                    new NotBlank([
                        'message' => 'Debe seleccionar un usuario.',
                    ]),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurante::class,
        ]);
    }
}
