<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Editions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                'by_reference' => false,
            ]);

        //Data validation for the edition date, endDate > startDate
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $edition = $event->getData();
            if ($edition->getStartDate() && $edition->getEndDate() &&
                $edition->getEndDate() <= $edition->getStartDate()) {
                $event->getForm()->get('endDate')->addError(new FormError(
                    'The end date must be after the start date'
                ));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Editions::class,
        ]);
    }
}
