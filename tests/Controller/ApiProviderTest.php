<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Provider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use http\Env\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiProviderTest extends ApiTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/providers/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Provider::class);

        $this->manager->flush();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testIndex(): void
    {
        $response = $this->client->request('GET', '/api/v1/providers', ["headers" => ["Accept: application/json"]]);

        self::assertResponseStatusCodeSame(200);

        $this->assertCount(82, $response->toArray()['data']);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testNewFailsIfInvalidDataProvided(): void
    {
        $response = $this->client->request('POST', '/api/v1/providers', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'text' => 'Proves',
                'author' => '/api/users/1'
            ]]);

        self::assertResponseStatusCodeSame(400);

    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testNew(): void
    {
        $response = $this->client->request('POST', '/api/v1/providers', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'address' => 'Mar street',
                'dni' => '11111111H',
                'phone' => '604433075',
                'email' => 'juan@gmail.com',
                'cif' => '11111111H',
                'LOPDdoc' => 'LOPD',
                'bankTitle' => 'bankTitle'
            ]]);

        $providerData = $response->toArray()["data"];


        self::assertResponseStatusCodeSame(201);

        self::assertSame("Mar street", $providerData["address"]);
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Provider();
        $fixture->setAddress('My Title');
        $fixture->setDni('My Title');
        $fixture->setPhone('My Title');
        $fixture->setEmail('My Title');
        $fixture->setCif('My Title');
        $fixture->setManagerNif('My Title');
        $fixture->setLOPDdoc('My Title');
        $fixture->setbankTitle('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Provider');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Provider();
        $fixture->setAddress('Value');
        $fixture->setDni('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setCif('Value');
        $fixture->setManagerNif('Value');
        $fixture->setLOPDdoc('Value');
        $fixture->setbankTitle('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'professional[address]' => 'Something New',
            'professional[dni]' => 'Something New',
            'professional[phone]' => 'Something New',
            'professional[email]' => 'Something New',
            'professional[cif]' => 'Something New',
            'professional[managerNif]' => 'Something New',
            'professional[LOPDdoc]' => 'Something New',
            'professional[bankTitle]' => 'Something New',
        ]);

        self::assertResponseRedirects('/providers/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getDni());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getCif());
        self::assertSame('Something New', $fixture[0]->getManagerNif());
        self::assertSame('Something New', $fixture[0]->getLOPDdoc());
        self::assertSame('Something New', $fixture[0]->getbankTitle());
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Provider();
        $fixture->setAddress('Value');
        $fixture->setDni('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setCif('Value');
        $fixture->setManagerNif('Value');
        $fixture->setLOPDdoc('Value');
        $fixture->setbankTitle('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/providers/');
        self::assertSame(0, $this->repository->count([]));
    }
}
