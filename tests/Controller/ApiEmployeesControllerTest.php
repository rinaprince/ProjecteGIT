<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Employee;
use App\Entity\Professional;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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
                'lastname' => 'manele',
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
        //$this->markTestIncomplete();
        $response = $this->client->request('GET', '/api/v1/employees/1', ["headers" => ["Accept: application/json"]]);


        self::assertResponseStatusCodeSame(200);
        self::assertSame('Administrador', $response->toArray()['data']['name']);



        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $response = $this->client->request('PUT', '/api/v1/employees/3', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'name' => 'Ornitorrinco',
                'lastname' => 'Thompson',
                'type' => 'administrator',
                'username' => 'orni',
                'password' => 'torrinco',
            ]]);

        $employeeData = $response->toArray()["data"];

        self::assertResponseStatusCodeSame(201);

        self::assertSame("Ornitorrinco", $employeeData["name"]);

    }

    public function testRemove(): void
    {
        $response = $this->client->request('DELETE', '/api/v1/employees/1', ["headers" => ["Accept: application/json"]]);

        $this->assertCount(81, $this->repository->findAll());
    }
}
