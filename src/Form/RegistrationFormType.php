<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $builder->getData();
        $builder
            ->add('email', TextType::class, ['label' => 'Email', 'required' => false, 'constraints' => [new Assert\Email(), new Assert\NotBlank()]])
            ->add('nombre', TextType::class, ['required' => false,'constraints' => new Assert\NotBlank()])
            ->add('contacto', TextType::class, ['required' => false,'constraints' => new Assert\NotBlank()])
            ->add('puntos', IntegerType::class, ['required' => false,'constraints' => new Assert\Positive()])
            ->add('agreeTerms', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Assert\IsTrue([
                        'message' => 'Debes de aceptar los términos',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Por favor introduzca una contraseña',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    
        // ...
    
        // Validar el formulario
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
    
            $validator = Validation::createValidator();
            $violations = $validator->validate($data);
    
            if (count($violations) > 0) {
                foreach ($violations as $violation) {
                    // Manejar los errores de validación
                    $form->addError(new FormError($violation->getMessage()));
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
