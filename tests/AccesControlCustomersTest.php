<?php


use Generator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccesControlCustomersTest extends WebTestCase
{

    /**
     * @dataProvider getUrlCustomersFromAdmin()
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessCustomersAdmin($uri, $expectedStatusCode): void
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
     * @dataProvider getUrlCustomersFromAdministrative()
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessCustomersAdministrative($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(\App\Repository\LoginRepository::class);
        $testUser = $userRepository->findOneByUsername('administrative');
        $client->loginUser($testUser);
        $client->catchExceptions(false);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }


    public function getUrlCustomersFromAdmin(): Generator
    {
        // Employees
        yield "Employee index" => ['/employees', Response::HTTP_OK];

        yield "Employee new" => ['/employees/new', Response::HTTP_OK];
        yield "Employee show" => ['/employees/2/details', Response::HTTP_OK];
        yield "Employee edit" => ['/employees/2/edit', Response::HTTP_OK];
        //yield "Employee delete" => ['/employees/2/delete', Response::HTTP_OK];

        // Customer
        yield "Customer index" => ['/customers', Response::HTTP_OK];
        // Customer Privat
        yield "Private Customer new" => ['/private/customers/new', Response::HTTP_OK];
        yield "Private Customer show" => ['/private/customers/1', Response::HTTP_OK];
        yield "Private Customer edit" => ['/private/customers/1/edit', Response::HTTP_OK];
        //yield "Private Customer delete" => ['/private/customers/1', Response::HTTP_OK];

        // Customer Professional
        yield "Professional Customer new" => ['/professional/customers/new', Response::HTTP_OK];
        yield "Professional Customer show" => ['/professional/customers/4', Response::HTTP_OK];
        yield "Professional Customer edit" => ['/professional/customers/4/edit', Response::HTTP_OK];
        //yield "Professional Customer delete" => ['/professional/customers/1', Response::HTTP_OK];
    }

    public function getUrlCustomersFromAdministrative(): Generator
    {
        // Employees
        yield "Employee index" => ['/employees', Response::HTTP_OK];

        yield "Employee new" => ['/employees/new', Response::HTTP_FORBIDDEN];
        yield "Employee show" => ['/employees/2/details', Response::HTTP_OK];
        yield "Employee edit" => ['/employees/2/edit', Response::HTTP_FORBIDDEN];
        //yield "Employee delete" => ['/employees/2/delete', Response::HTTP_OK];

        // Customer
        yield "Customer index" => ['/customers', Response::HTTP_OK];
        // Customer Privat
        yield "Private Customer new" => ['/private/customers/new', Response::HTTP_OK];
        yield "Private Customer show" => ['/private/customers/1', Response::HTTP_OK];
        yield "Private Customer edit" => ['/private/customers/1/edit', Response::HTTP_FORBIDDEN];
        //yield "Private Customer delete" => ['/private/customers/1', Response::HTTP_OK];

        // Customer Professional
        yield "Professional Customer new" => ['/professional/customers/new', Response::HTTP_OK];
        yield "Professional Customer show" => ['/professional/customers/4', Response::HTTP_OK];
        yield "Professional Customer edit" => ['/professional/customers/4/edit', Response::HTTP_FORBIDDEN];
        //yield "Professional Customer delete" => ['/professional/customers/1', Response::HTTP_OK];
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
