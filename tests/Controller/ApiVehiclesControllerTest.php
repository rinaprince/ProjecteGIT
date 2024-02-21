<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Employee;
use App\Entity\Professional;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiVehiclesControllerTest extends ApiTestCase
{

    private KernelBrowser $employee;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/vehicles/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Vehicle::class);

//        foreach ($this->repository->findAll() as $object) {
//            $this->manager->remove($object);
//        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $response = $this->client->request('GET', '/api/v1/vehicles', ["headers" => ["Accept: application/json"]]);

        self::assertResponseStatusCodeSame(200);
        //self::assertPageTitleContains('Employees index');

        $this->assertCount(20, $response->toArray()['data']);
        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNewFailsIfInvalidDataProvided(): void
    {
        //$this->markTestIncomplete();
        $response = $this->client->request('POST', '/api/v1/vehicles', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'text' => 'Proves',
                'author' => '/api/users/1'
            ]]);

        self::assertResponseStatusCodeSame(400);

        //self::assertSame(82, $this->repository->count([]));
    }

    public function testNew(): void
    {
        //$this->markTestIncomplete();
        $response = $this->client->request('POST', '/api/v1/vehicles', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'plate' => '1234HGH',
                'observedDamages' => 'Hola',
                'kilometers' => '9990',
                'buyPrice' => '450000',
                'sellPrice' => '1000000',
                'fuel' => 'gasolina',
                'iva' => '21',
                'description' => 'Jesus',
                'chassisNumber' => '123123',
                'gearShit' => 'manual',
                'isNew' => '1',
                'transportIncluded' => '0',
                'color' => 'Red',
                'registrationDate' => '20-02-2024',
            ]]);

        $vehicleData = $response->toArray()["data"];


        self::assertResponseStatusCodeSame(201);

        self::assertSame("1234HGH", $vehicleData["plate"]);
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicle();
        $fixture->setPlate('My Title');
        $fixture->setObservedDamages('My Title');
        $fixture->setKilometers('My Title');
        $fixture->setBuyPrice('My Title');
        $fixture->setSellPrice('My Title');
        $fixture->setFuel('My Title');
        $fixture->setIva('My Title');
        $fixture->setDescription('My Title');
        $fixture->setChassisNumber('My Title');
        $fixture->setGearShit('My Title');
        $fixture->setIsNew('My Title');
        $fixture->setTransportIncluded('My Title');
        $fixture->setColor('My Title');
        $fixture->setRegistrationDate("20-02-2024");

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Vehicle');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicle();
        $fixture->setPlate('Value');
        $fixture->setObservedDamages('Value');
        $fixture->setKilometers('Value');
        $fixture->setBuyPrice('Value');
        $fixture->setSellPrice('Value');
        $fixture->setFuel('Value');
        $fixture->setIva('Value');
        $fixture->setDescription('Value');
        $fixture->setChassisNumber('Value');
        $fixture->setGearShit('Value');
        $fixture->setIsNew('Value');
        $fixture->setTransportIncluded('Value');
        $fixture->setColor('Value');
        $fixture->setRegistrationDate("20-02-2024");

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'vehicle[plate]' => 'Something New',
            'vehicle[observedDamages]' => 'Something New',
            'vehicle[kilometers]' => 'Something New',
            'vehicle[buyPrice]' => 'Something New',
            'vehicle[sellPrice]' => 'Something New',
            'vehicle[fuel]' => 'Something New',
            'vehicle[iva]' => 'Something New',
            'vehicle[description]' => 'Something New',
            'vehicle[chassisNumber]' => 'Something New',
            'vehicle[gearShift]' => 'Something New',
            'vehicle[isNew]' => 'Something New',
            'vehicle[transportIncluded]' => 'Something New',
            'vehicle[color]' => 'Something New',
            'vehicle[registrationDate]' => 'Something New',
        ]);

        self::assertResponseRedirects('/vehicles/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getPlate());
        self::assertSame('Something New', $fixture[0]->getObervedDamages());
        self::assertSame('Something New', $fixture[0]->getKilometers());
        self::assertSame('Something New', $fixture[0]->getBuyPrice());
        self::assertSame('Something New', $fixture[0]->getSellPrice());
        self::assertSame('Something New', $fixture[0]->getFuel());
        self::assertSame('Something New', $fixture[0]->getIva());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getChassisNumber());
        self::assertSame('Something New', $fixture[0]->getGearShift());
        self::assertSame('Something New', $fixture[0]->getIsNew());
        self::assertSame('Something New', $fixture[0]->getTransportIncluded());
        self::assertSame('Something New', $fixture[0]->getColor());
        self::assertSame('Something New', $fixture[0]->getRegistrationDate());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicle();
        $fixture->setPlate('Value');
        $fixture->setObservedDamages('Value');
        $fixture->setKilometers('Value');
        $fixture->setBuyPrice('Value');
        $fixture->setSellPrice('Value');
        $fixture->setFuel('Value');
        $fixture->setIva('Value');
        $fixture->setDescription('Value');
        $fixture->setChassisNumber('Value');
        $fixture->setGearShit('Value');
        $fixture->setIsNew('Value');
        $fixture->setTransportIncluded('Value');
        $fixture->setColor('Value');
        $fixture->setRegistrationDate("20-02-2024");

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/vehicles/');
        self::assertSame(0, $this->repository->count([]));
    }
}
