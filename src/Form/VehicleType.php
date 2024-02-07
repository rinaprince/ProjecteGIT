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
            ->add('observedDamages', CKEditorType::class)
            ->add('kilometers')
            ->add('buyPrice')
            ->add('sellPrice')
            ->add('fuel')
            ->add('iva')
            ->add('description', CKEditorType::class)
            ->add('chassisNumber')
            ->add('gearShit')
            ->add('isNew')
            ->add('transportIncluded')
            ->add('color')
            ->add('registrationDate')
            ->add('model', ModelAutocompleteField::class,[])
            ->add('provider', EntityType::class, [
                'class' => Provider::class,
                'choice_label' => 'businessName',

            ])
            ->add('vehicleOrder', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}