<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Employee;
use App\Entity\Professional;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiEmployeesControllerTest extends ApiTestCase
{

    private KernelBrowser $employee;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/employees/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Employee::class);

//        foreach ($this->repository->findAll() as $object) {
//            $this->manager->remove($object);
//        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $response = $this->client->request('GET', '/api/v1/employees', ["headers" => ["Accept: application/json"]]);

        self::assertResponseStatusCodeSame(200);
        //self::assertPageTitleContains('Employees index');

        $this->assertCount(82, $response->toArray()['data']);
        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNewFailsIfInvalidDataProvided(): void
    {
        //$this->markTestIncomplete();
        $response = $this->client->request('POST', '/api/v1/employees', [
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
        $response = $this->client->request('POST', '/api/v1/employees', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'name' => 'Manolo',
                'lastname' => '',
                'type' => 'administrative',
                'username' => 'ManoloJ',
                'password' => '1234',
            ]]);

        $employeeData = $response->toArray()["data"];


        self::assertResponseStatusCodeSame(201);

        self::assertSame("Manolo", $employeeData["name"]);
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employee();
        $fixture->setName('My Title');
        $fixture->setLastname('My Title');
        $fixture->setAddress('My Title');
        $fixture->setDni('My Title');
        $fixture->setPhone('My Title');
        $fixture->setEmail('My Title');
        $fixture->setCif('My Title');
        $fixture->setManagerNif('My Title');
        $fixture->setLOPDdoc('My Title');
        $fixture->setBussinessName('My Title');
        $fixture->setConstitutionWriting('My Title');
        $fixture->setSubscription('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Professional');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Professional();
        $fixture->setName('Value');
        $fixture->setLastname('Value');
        $fixture->setAddress('Value');
        $fixture->setDni('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setCif('Value');
        $fixture->setManagerNif('Value');
        $fixture->setLOPDdoc('Value');
        $fixture->setBussinessName('Value');
        $fixture->setConstitutionWriting('Value');
        $fixture->setSubscription('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'professional[name]' => 'Something New',
            'professional[lastname]' => 'Something New',
            'professional[address]' => 'Something New',
            'professional[dni]' => 'Something New',
            'professional[phone]' => 'Something New',
            'professional[email]' => 'Something New',
            'professional[cif]' => 'Something New',
            'professional[managerNif]' => 'Something New',
            'professional[LOPDdoc]' => 'Something New',
            'professional[bussinessName]' => 'Something New',
            'professional[constitutionWriting]' => 'Something New',
            'professional[subscription]' => 'Something New',
        ]);

        self::assertResponseRedirects('/professional/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getLastname());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getDni());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getCif());
        self::assertSame('Something New', $fixture[0]->getManagerNif());
        self::assertSame('Something New', $fixture[0]->getLOPDdoc());
        self::assertSame('Something New', $fixture[0]->getBussinessName());
        self::assertSame('Something New', $fixture[0]->getConstitutionWriting());
        self::assertSame('Something New', $fixture[0]->getSubscription());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Professional();
        $fixture->setName('Value');
        $fixture->setLastname('Value');
        $fixture->setAddress('Value');
        $fixture->setDni('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setCif('Value');
        $fixture->setManagerNif('Value');
        $fixture->setLOPDdoc('Value');
        $fixture->setBussinessName('Value');
        $fixture->setConstitutionWriting('Value');
        $fixture->setSubscription('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/professional/');
        self::assertSame(0, $this->repository->count([]));
    }
}
