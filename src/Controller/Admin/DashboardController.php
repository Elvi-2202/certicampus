<?php
namespace App\Controller\Admin;

use App\Repository\CertifiedRepository;
use App\Repository\DiplomaRepository;
use App\Repository\SchoolRepository;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(
        CertifiedRepository $certifiedRepo,
        DiplomaRepository $diplomaRepo,
        SchoolRepository $schoolRepo,
        TrainingRepository $trainingRepo,
    ): Response {
        $lastDiplomas = $diplomaRepo->findBy([], ['generated_at' => 'DESC'], 3);
        $lastCertified = $certifiedRepo->findBy([], ['graduationDate' => 'DESC'], 3);

        return $this->render('admin/dashboard/index.html.twig', [
            'count_certified'  => $certifiedRepo->count(),
            'count_diplomas'   => $diplomaRepo->count(),
            'count_schools'    => $schoolRepo->count(),
            'count_trainings'  => $trainingRepo->count(),
            'last_diplomas'    => $lastDiplomas,
            'last_certified'   => $lastCertified,
        ]);
    }
}