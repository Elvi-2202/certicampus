<?php

namespace App\Controller\Client;

use App\Repository\CertifiedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client/etudiants')]
final class CertifiedController extends AbstractController
{
    #[Route('', name: 'app_client_student_list', methods: ['GET', 'POST'])]
    public function index(Request $request, CertifiedRepository $certifiedRepo): Response
    {
        // Gestion de la sélection et génération/publication de masse
        if ($request->isMethod('POST')) {
            $selectedIds = $request->request->all('selected_students');
            $action = $request->request->get('action');

            if (!empty($selectedIds)) {
                $students = $certifiedRepo->findBy(['id' => $selectedIds]);
                
                if ($action === 'generate') {
                    $this->addFlash('success', count($students) . ' diplôme(s) en cours de génération.');
                } elseif ($action === 'publish') {
                    $this->addFlash('success', count($students) . ' diplôme(s) publié(s).');
                }

                return $this->redirectToRoute('app_client_student_list');
            }
        }

        // Gestion de la barre de recherche
        $searchQuery = $request->query->get('search');
        if ($searchQuery) {
            $students = $certifiedRepo->createQueryBuilder('c')
                ->where('c.firstname LIKE :q OR c.lastname LIKE :q')
                ->setParameter('q', '%'.$searchQuery.'%')
                ->getQuery()
                ->getResult();
        } else {
            $students = $certifiedRepo->findAll();
        }

        // Rendu connecté sur ton dossier existant "student"
        return $this->render('client/student/index.html.twig', [
            'certified_students' => $students,
        ]);
    }
}