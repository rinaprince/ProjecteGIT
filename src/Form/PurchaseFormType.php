<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('document', ChoiceType::class, [
                'choices' => [
                    'DNI' => 'DNI',
                    'NIE' => 'NIE',
                    'Passaport' => 'Passaport',
                ],
                'label' => 'Documentació'
            ])
            ->add('documentNumber', TextType::class, [
                'label' => 'Número de document'
            ])
            ->add('emailInput', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('phoneInput', TelType::class, [
                'label' => 'Mòbil'
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'choices' => [
                    'PayPal' => 'PayPal',
                    'Visa' => 'Visa',
                    'MasterCard' => 'MasterCard',
                ],
                'label' => 'Mètode'
            ])
            ->add('numberInput', TextType::class, [
                'label' => 'Número de la targeta'
            ])
            ->add('termsCheckbox', CheckboxType::class, [
                'label' => 'Acceptar termes i condicions d\'ús.',
                'required' => false
            ])
            ->add('addressName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('addressLastName', TextType::class, [
                'label' => 'Cognoms'
            ])
            ->add('provinceAddress', TextType::class, [
                'label' => 'Provincia'
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Codi postal'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ciutat'
            ])
            ->add('address', TextType::class, [
                'label' => 'Direcció'
            ])
            ->add('details', TextType::class, [
                'label' => 'Pis i porta'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

}