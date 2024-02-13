<?php

namespace App\Form;

use App\Entity\Professional;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' )
            ->add('lastname')
            ->add('address')
            ->add('dni')
            ->add('phone')
            ->add('email')
            ->add('cif')
            ->add('managerNif')
            ->add('LOPDdoc', FileType::class)
            ->add('bussinessName')
            ->add('constitutionWriting',FileType::class)
            ->add('login', LoginType::class)
            ->add('subscription',CheckboxType::class,array('required'=> false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
