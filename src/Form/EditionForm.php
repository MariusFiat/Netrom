<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Editions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EditionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('lineup', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => function(Artist $artist) {
                    return $artist->getName() . ' (' . $artist->getMusicGenre() . ')';
                },
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control select2-multiple',
                    'data-placeholder' => 'Select artists...'
                ],
                'by_reference' => false, // This is crucial
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Editions::class,
        ]);
    }
}
