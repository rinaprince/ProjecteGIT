<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class,
                [
                    'required' => false,
                    'allow_delete' => true,
                    'delete_label' => 'Esborrar imatge',
                    'download_label' => '...',
                    'download_uri' => false,
                    'image_uri' => true,
                    'asset_helper' => true,
                    'imagine_pattern' => 'vehicle_thumb']
            )

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
