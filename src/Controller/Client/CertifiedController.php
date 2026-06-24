<?php

namespace App\Controller\Client;

use App\Repository\CertifiedRepository;
use App\Service\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client/etudiants')]
final class CertifiedController extends AbstractController
{
    public function __construct(
        private QrCodeService $qrCodeService
    ) {}

    #[Route('', name: 'app_client_student_list', methods: ['GET', 'POST'])]
    public function index(Request $request, CertifiedRepository $certifiedRepo): Response
    {
        if ($request->isMethod('POST')) {
            $selectedIds = $request->request->all('selected_students');
            $action = $request->request->get('action');

            if (!empty($selectedIds)) {
                $students = $certifiedRepo->findBy(['id' => $selectedIds]);
                if ($action === 'generate') {
                    $qrCodes = [];
                    foreach ($students as $student) {
                        $qrCodes[$student->getId()] = $this->qrCodeService->generateForCertified($student);
                    }
                    $request->getSession()->set('qr_codes', $qrCodes);
                    $this->addFlash('success', count($students) . ' diplôme(s) en cours de génération.');
                    return $this->redirectToRoute('app_client_student_list');
                } elseif ($action === 'publish') {
                    $this->addFlash('success', count($students) . ' diplôme(s) publié(s).');
                }

                return $this->redirectToRoute('app_client_student_list');
            }
        }

        $searchQuery = $request->query->get('search');
        if ($searchQuery) {
            $students = $certifiedRepo->createQueryBuilder('c')
                ->where('c.firstname LIKE :q OR c.lastname LIKE :q')
                ->setParameter('q', '%' . $searchQuery . '%')
                ->getQuery()
                ->getResult();
        } else {
            $students = $certifiedRepo->findAll();
        }

        return $this->render('client/student/index.html.twig', [
            'certified_students' => $students,
            'qr_codes' => $request->getSession()->get('qr_codes', []),
        ]);
    }
}