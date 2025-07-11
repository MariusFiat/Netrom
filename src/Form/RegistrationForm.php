<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an email']),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your first name'])
                ]
            ])
            ->add('lastName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your last name'])
                ]
            ])
            ->add('age', IntegerType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your age']),
                    new Range([
                        'min' => 13,
                        'minMessage' => 'You must be at least {{ limit }} years old'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'registration_form',
        ]);
    }
}
