<?php

namespace App\Tests\Controller\School;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TrainingControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/school/training');

        self::assertResponseRedirects('/login');
    }
}
