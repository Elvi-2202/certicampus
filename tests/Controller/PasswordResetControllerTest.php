<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PasswordResetControllerTest extends WebTestCase
{
    public function testPasswordResetWithValidEmail(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/api/school/password-reset',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'school@example.com'])
        );

        $this->assertResponseStatusCodeSame(204);
    }

    public function testPasswordResetWithInvalidEmail(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/api/school/password-reset',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'not-an-email'])
        );

        $this->assertResponseStatusCodeSame(400);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Invalid', $response['message']);
    }

    public function testPasswordResetWithMissingEmail(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/api/school/password-reset',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $this->assertResponseStatusCodeSame(400);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($response['success']);
        $this->assertStringContainsString('required', strtolower($response['message']));
    }

    public function testPasswordResetWithEmptyEmail(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/api/school/password-reset',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => ''])
        );

        $this->assertResponseStatusCodeSame(400);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($response['success']);
    }

    public function testPasswordResetWithInvalidJson(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/api/school/password-reset',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid json'
        );

        $this->assertResponseStatusCodeSame(400);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Invalid JSON', $response['message']);
    }

    public function testPasswordResetGetMethodNotAllowed(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/api/school/password-reset');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testPasswordResetPutMethodNotAllowed(): void
    {
        $client = static::createClient();
        
        $client->request('PUT', '/api/school/password-reset');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testPasswordResetDeleteMethodNotAllowed(): void
    {
        $client = static::createClient();
        
        $client->request('DELETE', '/api/school/password-reset');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testPasswordResetResponseHeaders(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/api/school/password-reset',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'school@example.com'])
        );

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testPasswordResetWithWhitespaceEmail(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/api/school/password-reset',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => '  '])
        );

        $this->assertResponseStatusCodeSame(400);
    }
}
