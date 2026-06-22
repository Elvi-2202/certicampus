<?php

namespace App\Tests\Controller\Client\;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CertifiedControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/client/certified');

        self::assertResponseIsSuccessful();
    }
}
