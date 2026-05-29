<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TemplateController extends AbstractController
{
    #[Route('/admin/template', name: 'app_admin_template')]
    public function index(): Response
    {
        return $this->render('admin/template/index.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
}
