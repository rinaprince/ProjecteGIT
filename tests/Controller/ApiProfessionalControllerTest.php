<?php

// tests/Controller/ApiProfessionalControllerTest.php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Professional;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ApiProfessionalControllerTest extends ApiTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/api/v1/professionals/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Professional::class);

        // Eliminar todos los profesionales antes de cada prueba (descomenta si es necesario)
        // foreach ($this->repository->findAll() as $professional) {
        //     $this->manager->remove($professional);
        // }

        // $this->manager->flush();
    }
    public function testIndex(): void
    {
        $response = $this->client->request('GET', '/api/v1/professionals', ["headers" => ["Accept: application/json"]]);

        self::assertResponseStatusCodeSame(200);
        //self::assertPageTitleContains('Employees index');

        $this->assertCount(5, $response->toArray()['data']);
    }

    public function testNewFailsIfInvalidDataProvided(): void
    {
        //$this->markTestIncomplete();
        $response = $this->client->request('POST', '/api/v1/professionals', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'text' => 'Proves',
                'author' => '/api/users/1'
            ]]);

        static::assertResponseStatusCodeSame(400);

        //self::assertSame(82, $this->repository->count([]));
    }

    public function testNew(): void
    {
        //$this->markTestIncomplete();
        $response = $this->client->request('POST', '/api/v1/professionals', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'name' => 'Manolo',
                'lastname' => 'Hola',
                'address' => 'Manolo manolito',
                'dni' => '123456789Z',
                'phone' => '666666666',
                'email' => 'manolo@gmail.com',
                'cif' => '123456789Z',
                'nif' => '123456789Z',
                'bussinessName' => 'Manolo',
                'constitutionWriting' => 'gjhguywygwy',
                'subscription' => '1',
                'username' => 'ManoloJ',
                'password' => '1234',
            ]]);

        $employeeData = $response->toArray()["data"];

        static::assertResponseStatusCodeSame(201);
        static::assertSame("Manolo", $employeeData["name"]);
    }

    public function testRemove(): void
    {
        $response = $this->client->request('DELETE', '/api/v1/professionals/2', ["headers" => ["Accept: application/json"]]);

        $this->assertCount(4, $this->repository->findAll());
    }



}
