<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArtistTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Artist Name',
                'attr' => [
                    'placeholder' => 'Enter artist name',
                    'class' => 'form-control'
                ]
            ])
            ->add('musicGenre', ChoiceType::class, [
                'label' => 'Music Genre',
                'choices' => [
                    'Rock' => 'Rock',
                    'Pop' => 'Pop',
                    'Hip Hop' => 'Hip Hop',
                    'Electronic' => 'Electronic',
                    'Jazz' => 'Jazz',
                    'Classical' => 'Classical',
                    'Metal' => 'Metal',
                    'R&B' => 'R&B',
                    'Country' => 'Country',
                    'Reggae' => 'Reggae'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
