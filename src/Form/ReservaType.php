<?php

namespace App\Form;

use App\Entity\Reserva;
use App\Entity\Restaurante;
use App\Entity\User;
use App\Entity\Mesa;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;



class ReservaType extends AbstractType
{
    private $horaSeleccionada;

    public function __construct(?DateTimeInterface $horaSeleccionada = null)
    {
        $this->horaSeleccionada = $horaSeleccionada;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fechaHora', TextType::class, [
                'data' => date('d/m/Y'), // Establecer la fecha actual como valor predeterminado en formato de texto
            ])
            ->add('hora', ChoiceType::class, [
                'choices' => [
                    'Horario de dia' => [
                        '13:30h' => '13:30',
                        '14:00h' => '14:00',
                        '14:30h' => '14:30',
                    ],
                    'Horario de noche' => [
                        '20:30h' => '20:30',
                        '21:00h' => '21:00',
                        '21:30h' => '21:30',
                    ],
                ],
                'placeholder' => 'Elige tu hora',
                'required' => true,
                'expanded' => false, // Si deseas mostrar el submenu en una lista desplegable 
                'multiple' => false, // Si deseas permitir la selección de múltiples opciones
                'constraints' => [
                    // new NotBlank([
                    //     'message' => 'El campo "fecha" no puede estar vacío.',
                    // ]),
                ],
            ])
            ->add('lugar', ChoiceType::class, [
                'choices' => [
                    'Terraza' => 'Terraza',
                    'Interior' => 'Interior',
                ],
                'required' => true,
                'label' => 'Elige donde desea comer',
            ])
            ->add('mesa', EntityType::class, [
                'required' => true,
                'class' => Mesa::class, 
                'placeholder' => 'Seleccione una opción', 
                // 'choice_label' => 'hola',
                'choice_attr' => function($choice, $key, $value) {
                    if ($key === 2) {
                        return ['class' => 'my-custom-class'];
                    }
                    return [];
                },
            ])
                
            ->add('usuario', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'nombre',
                'label' => 'Nombre Usuario',
                'disabled' => true,
                'attr' => [
                    'class' => 'd-none'
                ]
            ])

            ->add('restaurante', EntityType::class, [
                'class' => 'App\Entity\Restaurante',
                'choice_label' => 'nombre',
                'label' => 'Restaurante',
                'attr' => [
                    'class' => 'd-none'
                ],
                'disabled' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
            'restaurante' => null,
        ]);
    }
    private function getHorasDisponibles()
    {
        // Lógica para obtener las horas disponibles
        // Puedes obtenerlas de tu base de datos, de una API, etc.
        // Aquí se muestra un ejemplo estático:
        $horasDisponibles = [
            '09:00',
            '10:00',
            '11:00',
            // ...
        ];

        // Agrega la hora seleccionada a las horas disponibles
        if ($this->horaSeleccionada && !in_array($this->horaSeleccionada->format('H:i'), $horasDisponibles)) {
            $horasDisponibles[] = $this->horaSeleccionada->format('H:i');
        }

        return $horasDisponibles;
    }
}
