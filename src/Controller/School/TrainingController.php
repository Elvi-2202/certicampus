<?php

namespace App\Controller\School;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrainingController extends AbstractController
{
    #[Route('/school/training', name: 'app_school_training')]
    public function index(): Response
    {
        return $this->render('school/training/index.html.twig', [
            'controller_name' => 'TrainingController',
        ]);
    }
}
