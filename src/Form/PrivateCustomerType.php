<?php

namespace App\Form;

use App\Entity\PrivateCustomer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrivateCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('address')
            ->add('dni')
            ->add('phone')
            ->add('email')
            ->add('login', LoginType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PrivateCustomer::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
