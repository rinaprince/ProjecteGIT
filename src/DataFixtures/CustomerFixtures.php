<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Login;
use App\Entity\PrivateCustomer;
use App\Entity\Professional;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('es_ES');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 7; $i++) {
            // Decideix aleatoriamente si es crea un PrivateCustomer o un Professional
            $isPrivateCustomer = $this->faker->boolean;

            // Crea la instancia del client
            $customer = $isPrivateCustomer ? new PrivateCustomer() : new Professional();

            $customer->setName($this->faker->firstNameMale());
            $customer->setLastname($this->faker->lastName());
            $customer->setAddress($this->faker->address());
            $customer->setDni($this->faker->dni());
            $customer->setPhone($this->faker->phoneNumber());
            $customer->setEmail($this->faker->email());
            $customer->setDischarge(false);

            if ($customer instanceof Professional) {
                $customer->setCif($this->faker->regexify('/^[A-Z0-9]{9}$/'));
                $customer->setManagerNif($this->faker->dni());
                $customer->setLOPDdoc($this->faker->word . '.pdf');
                $customer->setBussinessName($this->faker->company);
                $customer->setConstitutionWriting($this->faker->word . '.pdf');
                $customer->setSubscription($this->faker->boolean);
                $customer->setDischarge(false);
            }

            $login = new Login();
            $login->setUsername($this->faker->userName);
            $login->setPassword($this->hasher->hashPassword($login, "1234"));

            if ($isPrivateCustomer)
                $login->setRole("ROLE_PRIVATE");
            else
                $login->setRole("ROLE_PROFESSIONAL");

            $customer->setLogin($login);

            $manager->persist($login);
            $manager->persist($customer);
        }


            // Crea la instancia del client
            $customer = new Professional();

            $customer->setName("Client");
            $customer->setLastname("Professional");
            $customer->setAddress($this->faker->address());
            $customer->setDni($this->faker->dni());
            $customer->setPhone($this->faker->phoneNumber());
            $customer->setEmail($this->faker->email());
            $customer->setDischarge(true);

            if ($customer instanceof Professional) {
                $customer->setCif($this->faker->regexify('/^[A-Z0-9]{9}$/'));
                $customer->setManagerNif($this->faker->dni());
                $customer->setLOPDdoc($this->faker->word . '.pdf');
                $customer->setBussinessName($this->faker->company);
                $customer->setConstitutionWriting($this->faker->word . '.pdf');
                $customer->setSubscription($this->faker->boolean);
                $customer->setDischarge(false);
            }

        $login = new Login();
        $login->setUsername("professional");
        $login->setPassword($this->hasher->hashPassword($login, "professional"));
        $login->setRole("ROLE_PROFESSIONAL");

        $customer->setLogin($login);

        $manager->persist($login);
        $manager->persist($customer);


        // Crea la instancia del client
        $customer = new PrivateCustomer();

        $customer->setName("Client");
        $customer->setLastname("Private");
        $customer->setAddress($this->faker->address());
        $customer->setDni($this->faker->dni());
        $customer->setPhone($this->faker->phoneNumber());
        $customer->setEmail($this->faker->email());
        $customer->setDischarge(false);

        $login = new Login();
        $login->setUsername("private");
        $login->setPassword($this->hasher->hashPassword($login, "private"));
        $login->setRole("ROLE_PRIVATE");

        $customer->setLogin($login);

        $manager->persist($login);
        $manager->persist($customer);


        $customer2 = new PrivateCustomer();

        $customer2->setName("Client");
        $customer2->setLastname("Private");
        $customer2->setAddress($this->faker->address());
        $customer2->setDni($this->faker->dni());
        $customer2->setPhone($this->faker->phoneNumber());
        $customer2->setEmail($this->faker->email());
        $customer->setDischarge(false);

        $login2 = new Login();
        $login2->setUsername("pepeprivate");
        $login2->setPassword($this->hasher->hashPassword($login, "pepeprivate"));
        $login2->setRole("ROLE_PRIVATE");

        $customer2->setLogin($login2);

        $manager->persist($login2);
        $manager->persist($customer2);

        $manager->flush();
    }
}


