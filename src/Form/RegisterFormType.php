<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class RegisterFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'mapped' => false,
                'constraints' => [new NotBlank()]
            ])
            ->add('lastName', TextType::class, [
                'mapped' => false,
                'constraints' => [new NotBlank()]
            ])
            ->add('age', IntegerType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 13, 'max' => 120])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true, // This should be true
            'csrf_field_name' => '_token', // Default field name
            'csrf_token_id' => 'registration' // Unique token ID for this form
        ]);
    }
}
