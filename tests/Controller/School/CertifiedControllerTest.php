<?php

namespace App\Tests\Controller\School\;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CertifiedControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/school/certified');

        self::assertResponseIsSuccessful();
    }
}
