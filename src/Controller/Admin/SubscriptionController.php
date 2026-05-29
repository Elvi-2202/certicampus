<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SubscriptionController extends AbstractController
{
    #[Route('/admin/subscription', name: 'app_admin_subscription')]
    public function index(): Response
    {
        return $this->render('admin/subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }
}
