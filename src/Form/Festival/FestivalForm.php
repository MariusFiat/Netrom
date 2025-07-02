<?php

namespace App\Form;
namespace App\Form\Festival;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
class FestivalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Festival Name'  // This is the human-readable label
            ])
            ->add('location', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Festival'])
        ;
    }
}
