<?php

namespace App\Controller\Admin; // Correction ici (pas d'antislash à la fin)

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SchoolController extends AbstractController
{
    #[Route('/admin/school', name: 'app_admin_school')]
    public function index(): Response
    {
        return $this->render('admin/school/index.html.twig', [ // Correction ici (un seul /)
            'controller_name' => 'SchoolController',
        ]);
    }
}