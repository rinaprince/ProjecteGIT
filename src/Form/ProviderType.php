<?php

namespace App\Form;

use App\Entity\Provider;
use Faker\Core\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('businessName', TextType::class,
                [
                    'label' => new TranslatableMessage('provider_type.business_name')
                ])
            ->add('email', TextType::class,
                [
                    'label' => new TranslatableMessage('provider_type.email')
                ]
            )
            ->add('phone', TextType::class,
                [
                    'label' => new TranslatableMessage('provider_type.phone')
                ]
            )
            ->add('dni', TextType::class)
            ->add('cif', TextType::class)
            ->add('address', TextType::class)
            ->add('bankTitleFile',VichFileType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => false,
            ])
            ->add('managerNif', TextType::class)
            ->add('LOPDdocFile',VichFileType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => false,
            ])
            ->add('constitutionArticleFile',VichFileType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}
