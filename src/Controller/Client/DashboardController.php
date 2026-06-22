<?php

namespace App\Controller\Client;

use App\Repository\CertifiedRepository;
use App\Repository\DiplomaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_client_dashboard')]
    public function index(
        DiplomaRepository $diplomaRepo,
        CertifiedRepository $certifiedRepo
    ): Response
    {
        return $this->render('client/dashboard/index.html.twig', [
            'total_certified' => $certifiedRepo->count([]),
            'total_diplomas' => $diplomaRepo->count([]),
            'certified_students' => $certifiedRepo->findAll(),
            'diplomas' => $diplomaRepo->findAll(),
        ]);
    }
}