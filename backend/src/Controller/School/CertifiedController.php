<?php

namespace App\Controller\School;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CertifiedController extends AbstractController
{
    #[Route('/school/certified', name: 'app_school_certified')]
    public function index(): Response
    {
        return $this->render('school/certified/index.html.twig', [
            'controller_name' => 'CertifiedController',
        ]);
    }
}
