<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ApiInvoicesControllerTest extends ApiTestCase
{
    private KernelBrowser $invoice;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/invoices/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Invoice::class);

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $response = $this->client->request('GET', '/api/v1/invoices', ["headers" => ["Accept: application/json"]]);

        self::assertResponseStatusCodeSame(200);
        //self::assertPageTitleContains('Invoices index');

        $this->assertCount(82, $response->toArray()['data']);
        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNewFailsIfInvalidDataProvided(): void
    {
        //$this->markTestIncomplete();
        $response = $this->client->request('POST', '/api/v1/invoices', [
            "headers" => ["Accept: application/json"],
            'json' => [
                'text' => 'Proves',
                'author' => '/api/users/1'
            ]]);

        self::assertResponseStatusCodeSame(400);

        //self::assertSame(82, $this->repository->count([]));
    }


}