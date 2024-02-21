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
        // Obtener todos los vehículos disponibles
        $vehicles = $this->vehicleRepository->findAll();

        // Obtener todos los clientes disponibles
        $customers = $this->privateCustomerRepository->findAll();

        // Contador para llevar el registro de cuántas órdenes se han creado
        $orderCount = 0;

        // Mientras no hayamos alcanzado el límite de 10 órdenes y aún queden vehículos disponibles
        while ($orderCount < 10 && count($vehicles) > 0) {
            // Crear una nueva orden
            $order = new Order();
            $order->setState($this->faker->randomElement(['In process', 'Completed']));
            $order->setDischarge(false);
            // Asignar un vehículo aleatorio a la orden
            $randomVehicleIndex = array_rand($vehicles);
            $vehicle = $vehicles[$randomVehicleIndex];
            $order->addVehicle($vehicle);

            // Eliminar el vehículo asignado de la lista de vehículos disponibles
            unset($vehicles[$randomVehicleIndex]);

            // Asignar un cliente aleatorio a la orden
            $randomCustomerIndex = array_rand($customers);
            $customer = $customers[$randomCustomerIndex];
            $order->setCustomer($customer);

            // Persistir la orden en la base de datos
            $manager->persist($order);

            // Incrementar el contador de órdenes creadas
            $orderCount++;
        }

        // Flushear los cambios a la base de datos
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