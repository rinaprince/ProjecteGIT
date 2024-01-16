<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Repository\PrivateCustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class OrderFixtures extends Fixture
{

    private Generator $faker;
    private PrivateCustomerRepository $privateCustomerRepository;

    public function __construct(PrivateCustomerRepository $privateCustomerRepository)
    {
        $this->faker = Factory::create('es_ES');
        $this->privateCustomerRepository = $privateCustomerRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $orders = [];

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setState($this->faker->randomElement(['In process', 'Completed']));

            $customers = $this->privateCustomerRepository->findAll();
            $customer = $customers[array_rand($customers)];

            $order->setCustomer($customer);

            $orders[] = $order;

            $manager->persist($order);
        }

        $manager->flush();
    }
}