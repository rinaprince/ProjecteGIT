<?php

namespace App\Test\Controller;

use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiModelControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/api/v1/models/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Model::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertJson($this->client->getResponse()->getContent());
    }

    public function testCreate(): void
    {
        $this->client->request('POST', $this->path . 'new', [], [], [], json_encode([
            'name' => 'GLA 250',
            'gearType' => 'Automático',
            'description' => 'GLA 250 211CV',
            'year' => 2023,
            'brand_id' => 202,
        ]));

        self::assertSame(201, $this->client->getResponse()->getStatusCode());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $modelId = $responseData['data']['id'];

        $this->client->request('GET', $this->path . $modelId);

        self::assertResponseStatusCodeSame(200);
        self::assertJson($this->client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
        $fixture = new Model();
        $fixture->setName('Value');
        $fixture->setGearType('Value');
        $fixture->setDescription('Value');
        $fixture->setYear(2021);
        $fixture->setBrand($this->manager->getRepository(\App\Entity\Brand::class)->find(1));

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('PUT', $this->path . 'edit/' . $fixture->getId(), [], [], [], json_encode([
            'name' => 'GLA 35 4MATIC',
            'gearType' => 'Automático',
            'description' => 'GLA 35 306CV',
            'year' => 2024,
            'brand_id' => 202,
        ]));

        self::assertSame(200, $this->client->getResponse()->getStatusCode());

        $updatedModel = $this->repository->find($fixture->getId());

        self::assertSame('GLA 35 4MATIC', $updatedModel->getName());
        self::assertSame('Automático', $updatedModel->getGearType());
        self::assertSame('GLA 35 306CV', $updatedModel->getDescription());
        self::assertSame(2024, $updatedModel->getYear());
    }

    public function testDelete(): void
    {
        $fixture = new Model();
        $fixture->setName('Value');
        $fixture->setGearType('Value');
        $fixture->setDescription('Value');
        $fixture->setYear(2021);
        $fixture->setBrand($this->manager->getRepository(\App\Entity\Brand::class)->find(1));

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('DELETE', $this->path . 'delete/' . $fixture->getId());

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertSame(0, $this->repository->count([]));
    }
}
