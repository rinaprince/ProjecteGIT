<?php

namespace App\Tests;

use Generator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppAvailabilityEquip3Test extends WebTestCase
{

    /**
     * @dataProvider getUrlListAnon()
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessAnonymous($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $client->catchExceptions(true);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);

    }

    public function getUrlListAnon(): Generator
    {
        //Invoices
        yield "Invoices index" => ['/invoices', Response::HTTP_FOUND];
        yield "Invoices create" => ['/invoices/new', Response::HTTP_FOUND];
        yield "Invoices show/delete" => ['/invoices/1', Response::HTTP_FOUND];
        yield "Invoices edit" => ['/invoices/1/edit', Response::HTTP_FOUND];

        //Orders
        yield "Orders index" => ['/orders', Response::HTTP_FOUND];
        yield "Orders create" => ['/orders/new', Response::HTTP_FOUND];
        yield "Orders show/delete" => ['/orders/1', Response::HTTP_FOUND];
        yield "Orders edit" => ['/orders/1/edit', Response::HTTP_FOUND];

        //Catalogue
        yield "Catalogue index" => ['/catalogue', Response::HTTP_OK];
        yield "Catalogue add vehicle" => ['/catalogue/add/1', Response::HTTP_FOUND];

        //Garage
        yield "Garage index" => ['/garage', Response::HTTP_FOUND];
        yield "Garage delete" => ['/garage/delete/1', Response::HTTP_FOUND];
        yield "Garage close" => ['/garage/close', Response::HTTP_FOUND];
        yield "Garage cancel" => ['/garage/cancel', Response::HTTP_FOUND];

        //Detail
        yield "Garage detail index" => ['/details/1', Response::HTTP_OK];
    }




    /**
     * @dataProvider getUrlListAdmin()
     */

    public function testAccessAdmin($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $userRepository = static::getContainer()->get(\App\Repository\LoginRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $client->loginUser($testUser);

        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);

    }

    public function getUrlListAdmin(): Generator
    {
        //Invoices
        yield "Invoices index" => ['/invoices', Response::HTTP_OK];
        yield "Invoices create" => ['/invoices/new', Response::HTTP_OK];
        yield "Invoices show/delete" => ['/invoices/1', Response::HTTP_OK];
        yield "Invoices edit" => ['/invoices/1/edit', Response::HTTP_OK];

        //Orders
        yield "Orders index" => ['/orders', Response::HTTP_OK];
        yield "Orders create" => ['/orders/new', Response::HTTP_OK];
        yield "Orders show/delete" => ['/orders/1', Response::HTTP_OK];
        yield "Orders edit" => ['/orders/1/edit', Response::HTTP_OK];

        //Catalogue
        yield "Catalogue index" => ['/catalogue', Response::HTTP_OK];
        yield "Catalogue add vehicle" => ['/catalogue/add/1', Response::HTTP_SEE_OTHER];

        //Garage
        yield "Garage index" => ['/garage', Response::HTTP_OK];
        yield "Garage delete" => ['/garage/delete/1', Response::HTTP_SEE_OTHER];
        yield "Garage close" => ['/garage/close', Response::HTTP_SEE_OTHER];
        yield "Garage cancel" => ['/garage/cancel', Response::HTTP_SEE_OTHER];

        //Detail
        yield "Detail index" => ['/details/1', Response::HTTP_OK];
    }
}
