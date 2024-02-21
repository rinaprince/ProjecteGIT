<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiProfessionalTest extends WebTestCase
{
    public function testGetAllProfessionals(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/professionals');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testGetProfessional(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/professionals/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testGetNonExistingProfessional(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/professionals/999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testCreateProfessional(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/v1/professionals/new', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name": "John",
            "lastname": "Doe",
            "address": "123 Main St",
            "dni": "123456789",
            "phone": "555-1234",
            "email": "john@example.com",
            "cif": "ABC123",
            "nif": "XYZ456",
            "bussinessName": "Doe Inc",
            "constitutionWriting": "hola.pdf",
            "subscription": "1"
        }');

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testCreateProfessionalWithInvalidData(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/v1/professionals/new', [], [], ['CONTENT_TYPE' => 'application/json'], '{
            "name": "John",
            "lastname": "Doe",
            "address": "123 Main St",
            "dni": "123456789",
            "phone": "555-1234",
            "email": "john@example.com",
            "cif": "ABC123",
            "nif": "XYZ456",
            "bussinessName": "Doe Inc",
            "subscription": "invalid"
        }');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
