<?php

namespace App\Controller\Client;

use App\Repository\CertifiedRepository;
use App\Repository\DiplomaRepository;
use App\Repository\SpecialityRepository;
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

            // Pour les cards étudiants
            'certified_students' => $certifiedRepo->findAll(),

            // Pour les cards diplômes
            'diplomas' => $diplomaRepo->findAll(),
        ]);
    }

    #[Route('/diplomes', name: 'app_client_diploma_list')]
    public function diplomas(
        DiplomaRepository $diplomaRepo,
        SpecialityRepository $specRepo
    ): Response
    {
        return $this->render('client/diploma/index.html.twig', [
            'diplomas' => $diplomaRepo->findAll(),
            'specialities' => $specRepo->findAll(),
        ]);
    }

    #[Route('/etudiants', name: 'app_client_student_list')]
    public function students(CertifiedRepository $certifiedRepo): Response
    {
        return $this->render('client/student/index.html.twig', [
            'certified_students' => $certifiedRepo->findAll(),
        ]);
    }
}