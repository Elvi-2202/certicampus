<?php

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    public function testAdminDashboardIsProtected(): void
    {
        $client = static::createClient();
        
        // On cible l'URL exacte de votre DashboardController
        $client->request('GET', '/admin/dashboard');

        // On vérifie que l'utilisateur anonyme est redirigé vers la page de login
        self::assertResponseRedirects('/login');
    }
}