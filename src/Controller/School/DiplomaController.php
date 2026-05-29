<?php

namespace App\Controller\School;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DiplomaController extends AbstractController
{
    #[Route('/school/diploma', name: 'app_school_diploma')]
    public function index(): Response
    {
        return $this->render('school/diploma/index.html.twig', [
            'controller_name' => 'DiplomaController',
        ]);
    }
}
