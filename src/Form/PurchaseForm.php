<?php

namespace App\Form;

use App\Entity\Purchase;
use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PurchaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('is_used', CheckboxType::class, [
                'label' => 'Is Used?',
                'required' => false,
            ])
            ->add('ticket_type_id', EntityType::class, [
                'class' => Ticket::class,
                'choice_label' => function (Ticket $ticket) {
                    return sprintf('%s - %.2f lei', $ticket->getType(), $ticket->getPrice());
                },
                'label' => 'Ticket Type',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
