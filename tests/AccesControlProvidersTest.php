<?php


use Generator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccesControlProvidersTest extends WebTestCase
{

    /**
     * @dataProvider getUrlProvidersFromAdmin()
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessAdmin($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(\App\Repository\LoginRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $client->loginUser($testUser);
        $client->catchExceptions(false);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    /**
     * @dataProvider getUrlProvidersFromAdministrative()
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessAdministrative($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(\App\Repository\LoginRepository::class);
        $testUser = $userRepository->findOneByUsername('administrative');
        $client->loginUser($testUser);
        $client->catchExceptions(false);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    /**
     * @dataProvider getUrlProvidersFromCustomers()
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessPrivateCustomers($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(\App\Repository\LoginRepository::class);
        $testUser = $userRepository->findOneByUsername('private');
        $client->loginUser($testUser);
        $client->catchExceptions(true);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }
    /**
     * @dataProvider getUrlProvidersFromCustomers()
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessProfessionalCustomers($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(\App\Repository\LoginRepository::class);
        $testUser = $userRepository->findOneByUsername('professional');
        $client->loginUser($testUser);
        $client->catchExceptions(true);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }


    public function getUrlProvidersFromAdmin() : Generator
    {
        // Providers
        yield "Provider index" => ['/providers/', Response::HTTP_OK];

        yield "Provider new" => ['/providers/new', Response::HTTP_OK];
        yield "Provider show" => ['/providers/2/details', Response::HTTP_OK];
        yield "Provider edit" => ['/providers/2/edit', Response::HTTP_OK];
        //yield "Provider delete" => ['/providers/2/delete', Response::HTTP_OK];
    }

    public function getUrlProvidersFromAdministrative() : Generator
    {
        // Providers
        yield "Provider index" => ['/providers/', Response::HTTP_OK];

        yield "Provider new" => ['/providers/new', Response::HTTP_OK];
        yield "Provider show" => ['/providers/2/details', Response::HTTP_OK];
        yield "Provider edit" => ['/providers/2/edit', Response::HTTP_OK];
        //yield "Provider delete" => ['/providers/2/delete', Response::HTTP_OK];
    }

    public function getUrlProvidersFromCustomers() : Generator
    {
        // Providers
        yield "Provider index" => ['/providers', Response::HTTP_FORBIDDEN];

        yield "Provider new" => ['/providers/new', Response::HTTP_FORBIDDEN];
        yield "Provider show" => ['/providers/2/details', Response::HTTP_FORBIDDEN];
        yield "Provider edit" => ['/providers/2/edit', Response::HTTP_FORBIDDEN];
        //yield "Provider delete" => ['/providers/2/delete', Response::HTTP_FORBIDDEN];
    }




    /*

public function testHomepageWorks(): void
{
    $client = static::createClient();
    $crawler = $client->request('GET', '/');


    $this->assertResponseStatusCodeSame(200);
}

public function testStatus404IfPageDoesntExist(): void
{
    $client = static::createClient();
    $client->catchExceptions(true);
    $crawler = $client->request('GET', '/asdf');


    $this->assertResponseStatusCodeSame(404);
}

*/

}
