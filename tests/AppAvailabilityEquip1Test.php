<?php


use Generator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppAvailabilityEquip1Test extends WebTestCase
{

    /**
     * @dataProvider getUrlList())
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccess($uri, $expectedStatusCode): void
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
     * @dataProvider getUrlListAdministrative())
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
        $client->catchExceptions(true);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    /**
     * @dataProvider getUrlListDeniedAccessByCustomers())
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessPrivate($uri, $expectedStatusCode): void
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
     * @dataProvider getUrlListDeniedAccessByCustomers())
     * @param $uri
     * @param $expectedStatusCode
     * @return void
     */
    public function testAccessProfessional($uri, $expectedStatusCode): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(\App\Repository\LoginRepository::class);
        $testUser = $userRepository->findOneByUsername('professional');
        $client->loginUser($testUser);
        $client->catchExceptions(true);
        $crawler = $client->request('HEAD', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function getUrlList(): Generator
    {

        // Employees (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Employee index" => ['/employees', Response::HTTP_OK];
        yield "Employee new" => ['/employees/new', Response::HTTP_OK];
        yield "Employee show" => ['/employees/2', Response::HTTP_OK];
        yield "Employee edit" => ['/employees/2/edit', Response::HTTP_OK];
        //yield "Employee delete" => ['/employees/2', Response::HTTP_OK];

        // Customer index (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Customer index" => ['/customers', Response::HTTP_OK];

        // Private Customer routes (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Private Customer new" => ['/private/customers/new', Response::HTTP_OK];
        yield "Private Customer show" => ['/private/customers/2', Response::HTTP_OK];
        yield "Private Customer edit" => ['/private/customers/2/edit', Response::HTTP_OK];
        //yield "Private Customer delete" => ['/private/customer/2', Response::HTTP_OK];

        // Professional Customer routes (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Professional Customer new" => ['/professional/customers/new', Response::HTTP_OK];
        yield "Professional Customer show" => ['/professional/customers/5', Response::HTTP_OK];
        yield "Professional Customer edit" => ['/professional/customers/5/edit', Response::HTTP_OK];
        //yield "Professional Customer delete" => ['/professional/2', Response::HTTP_OK];
    }

    public function getUrlListAdministrative(): Generator
    {

        // Employees (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Employee index" => ['/employees', Response::HTTP_OK];
        yield "Employee new" => ['/employees/new', Response::HTTP_FORBIDDEN];
        yield "Employee show" => ['/employees/2', Response::HTTP_OK];
        yield "Employee edit" => ['/employees/2/edit', Response::HTTP_FORBIDDEN];
        //yield "Employee delete" => ['/employees/2', Response::HTTP_OK];

        // Customer index (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Customer index" => ['/customers', Response::HTTP_OK];

        // Private Customer routes (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Private Customer new" => ['/private/customers/new', Response::HTTP_OK];
        yield "Private Customer show" => ['/private/customers/2', Response::HTTP_OK];
        yield "Private Customer edit" => ['/private/customers/2/edit', Response::HTTP_OK];
        //yield "Private Customer delete" => ['/private/customer/2', Response::HTTP_OK];

        // Professional Customer routes (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Professional Customer new" => ['/professional/customers/new', Response::HTTP_OK];
        yield "Professional Customer show" => ['/professional/customers/5', Response::HTTP_OK];
        yield "Professional Customer edit" => ['/professional/customers/5/edit', Response::HTTP_OK];
        //yield "Professional Customer delete" => ['/professional/2', Response::HTTP_OK];
    }

    public function getUrlListDeniedAccessByCustomers(): Generator
    {

        // Employees (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Employee index" => ['/employees', Response::HTTP_FORBIDDEN];
        yield "Employee new" => ['/employees/new', Response::HTTP_FORBIDDEN];
        yield "Employee show" => ['/employees/2', Response::HTTP_FORBIDDEN];
        yield "Employee edit" => ['/employees/2/edit', Response::HTTP_FORBIDDEN];
        //yield "Employee delete" => ['/employees/2', Response::HTTP_OK];

        // Customer index (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Customer index" => ['/customers', Response::HTTP_FORBIDDEN];

        // Private Customer routes (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Private Customer new" => ['/private/customers/new', Response::HTTP_FORBIDDEN];
        yield "Private Customer show" => ['/private/customers/2', Response::HTTP_FORBIDDEN];
        yield "Private Customer edit" => ['/private/customers/2/edit', Response::HTTP_FORBIDDEN];
        //yield "Private Customer delete" => ['/private/customer/2', Response::HTTP_OK];

        // Professional Customer routes (accessible by ROLE_ADMIN and ROLE_ADMINISTRATIVE)
        yield "Professional Customer new" => ['/professional/customers/new', Response::HTTP_FORBIDDEN];
        yield "Professional Customer show" => ['/professional/customers/5', Response::HTTP_FORBIDDEN];
        yield "Professional Customer edit" => ['/professional/customers/5/edit', Response::HTTP_FORBIDDEN];
        //yield "Professional Customer delete" => ['/professional/2', Response::HTTP_OK];
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
