<?php

namespace App\Tests\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DashboardControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/client/dashboard');

        self::assertResponseRedirects('/login');
    }
}