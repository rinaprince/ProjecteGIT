<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('price')
            ->add('date')
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
'choice_label' => 'id',
            ])
            ->add('customerOrder', EntityType::class, [
                'class' => Order::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
