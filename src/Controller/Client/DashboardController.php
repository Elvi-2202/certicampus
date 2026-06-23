<?php

namespace App\Controller\Client;

use App\Repository\CertifiedRepository;
use App\Repository\DiplomaRepository;
use App\Repository\SpecialityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_client_dashboard')]
    public function index(
        Request $request,
        DiplomaRepository $diplomaRepo,
        CertifiedRepository $certifiedRepo,
        SpecialityRepository $specRepo
    ): Response
    {
        // Récupérer le filtre de spécialité
        $specialityId = $request->query->get('speciality');
        
        if ($specialityId) {
            $certified_students = $certifiedRepo->findAll(); // À adapter selon les relations
        } else {
            $certified_students = $certifiedRepo->findAll();
        }

        return $this->render('client/dashboard/index.html.twig', [
            'total_certified' => $certifiedRepo->count([]),
            'total_diplomas' => $diplomaRepo->count([]),

            // Pour les cards étudiants
            'certified_students' => $certified_students,

            // Pour les cards diplômes
            'diplomas' => $diplomaRepo->findAll(),
            
            // Spécialités pour le filtre
            'specialities' => $specRepo->findAll(),
            
            // Filtre actif
            'selected_speciality' => $specialityId,
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

    #[Route('/diplomes/generer', name: 'app_client_diploma_generate', methods: ['POST'])]
    public function generateDiplomas(
        Request $request,
        DiplomaRepository $diplomaRepo,
        CertifiedRepository $certifiedRepo
    ): Response
    {
        $selectedStudents = $request->request->all('students') ?? [];
        
        if (empty($selectedStudents)) {
            $this->addFlash('error', 'Veuillez sélectionner au moins un étudiant');
        } else {
            $this->addFlash('success', count($selectedStudents) . ' diplôme(s) généré(s) avec succès');
        }

        return $this->redirectToRoute('app_client_dashboard');
    }

    #[Route('/diplomes/{id}/visualiser', name: 'app_client_diploma_view', methods: ['GET'])]
    public function viewDiploma(int $id, DiplomaRepository $diplomaRepo): Response
    {
        $diploma = $diplomaRepo->find($id);
        
        if (!$diploma) {
            throw $this->createNotFoundException('Diplôme non trouvé');
        }

        return $this->render('client/diploma/view.html.twig', [
            'diploma' => $diploma,
        ]);
    }

    #[Route('/diplomes/{id}/publier', name: 'app_client_diploma_publish', methods: ['POST'])]
    public function publishDiploma(int $id, DiplomaRepository $diplomaRepo): Response
    {
        $diploma = $diplomaRepo->find($id);
        
        if (!$diploma) {
            throw $this->createNotFoundException('Diplôme non trouvé');
        }

        // Logique de publication
        $this->addFlash('success', 'Diplôme publié avec succès');

        return $this->redirectToRoute('app_client_diploma_list');
    }
}