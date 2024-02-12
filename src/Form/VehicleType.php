<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Model;
use App\Entity\Order;
use App\Entity\Provider;
use App\Entity\Vehicle;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plate')
            ->add('observedDamages', HiddenType::class)
            ->add('kilometers')
            ->add('buyPrice')
            ->add('sellPrice')
            ->add('fuel',ChoiceType::class, [
                'choices'  => [
                    'Diesel' => "diesel",
                    'Gasolina' => "gasolina",
                    'Elèctric' => "electric",
                    'Hybrid' => "hybrid",
                ],
            ])
            ->add('iva')
            ->add('description', HiddenType::class)
            ->add('chassisNumber')
            ->add('gearShit',ChoiceType::class, [
                'choices'  => [
                    'Manual' => "manual",
                    'Automàtic' => "automatic",
                ],
            ])
            ->add('isNew')
            ->add('transportIncluded')
            ->add('color')
            ->add('registrationDate' ,DateType::class , [
                'widget' => 'single_text',
            ])
            ->add('model', ModelAutocompleteField::class,[])
            ->add('provider', EntityType::class, [
                'class' => Provider::class,
                'choice_label' => 'businessName',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
