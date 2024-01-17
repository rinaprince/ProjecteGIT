<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Repository\PrivateCustomerRepository;
use App\Repository\VehicleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{

    private Generator $faker;
    private PrivateCustomerRepository $privateCustomerRepository;

    private VehicleRepository $vehicleRepository;

    public function __construct(PrivateCustomerRepository $privateCustomerRepository, VehicleRepository $vehicleRepository)
    {
        $this->faker = Factory::create('es_ES');
        $this->privateCustomerRepository = $privateCustomerRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $orders = [];
        $vehicles = $this->vehicleRepository->findAll();

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setState($this->faker->randomElement(['In process', 'Completed']));
            //$vehicle = $vehicles[array_rand($vehicles)];

            /* Asigna a todos los vehiculos una orden, si no se comenta cada vez
               que se quiera hacer un Fix se debe hacer un rebuild */

            //$order->addVehicle(array_pop($vehicles));

            $customers = $this->privateCustomerRepository->findAll();
            $customer = $customers[array_rand($customers)];

            $order->setCustomer($customer);

            $orders[] = $order;

            $manager->persist($order);
        }
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [VehicleFixtures::class];
    }
}