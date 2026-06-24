<?php

namespace App\Controller\Client;

use App\Repository\DiplomaRepository;
use App\Repository\CertifiedRepository;
use App\Repository\SpecialityRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\TemplateDiplomaRepository;
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
    ): Response {
        $specialityId = $request->query->get('speciality');
        $certified_students = $certifiedRepo->findAll();

        // Statistiques pour les graphiques
        $totalCertified = $certifiedRepo->count([]);
        $totalDiplomas = $diplomaRepo->count([]);

        // Données par grade pour le graphique camembert
        $allCertified = $certifiedRepo->findAll();
        $gradeStats = [];
        foreach ($allCertified as $c) {
            $grade = $c->getGrade() ?? 'Non défini';
            $gradeStats[$grade] = ($gradeStats[$grade] ?? 0) + 1;
        }

        // Données par mois pour le graphique en barres (année en cours)
        $monthStats = array_fill(1, 12, 0);
        foreach ($allCertified as $c) {
            if ($c->getGraduationDate()) {
                $month = (int) $c->getGraduationDate()->format('n');
                $year  = (int) $c->getGraduationDate()->format('Y');
                if ($year === (int) date('Y')) {
                    $monthStats[$month]++;
                }
            }
        }

        return $this->render('client/dashboard/index.html.twig', [
            'total_certified'    => $totalCertified,
            'total_diplomas'     => $totalDiplomas,
            'certified_students' => $certified_students,
            'diplomas'           => $diplomaRepo->findAll(),
            'specialities'       => $specRepo->findAll(),
            'selected_speciality'=> $specialityId,
            'grade_stats'        => $gradeStats,
            'month_stats'        => array_values($monthStats),
        ]);
    }

    #[Route('/diplomes', name: 'app_client_diploma_list')]
    public function diplomas(
        DiplomaRepository $diplomaRepo,
        SpecialityRepository $specRepo
    ): Response {
        return $this->render('client/diploma/index.html.twig', [
            'diplomas'     => $diplomaRepo->findAll(),
            'specialities' => $specRepo->findAll(),
        ]);
    }

    #[Route('/diplomes/generer', name: 'app_client_diploma_generate', methods: ['POST'])]
    public function generateDiplomas(
        Request $request,
        CertifiedRepository $certifiedRepo,
        TemplateDiplomaRepository $templateRepo
    ): Response {
        $selectedStudents = $request->request->all('students') ?? [];

        if (empty($selectedStudents)) {
            $this->addFlash('error', 'Veuillez sélectionner au moins un étudiant');
            return $this->redirectToRoute('app_client_dashboard');
        }

        $template = $templateRepo->findOneBy([]);

        if (!$template) {
            $this->addFlash('error', 'Aucun template de diplôme configuré par l\'administrateur');
            return $this->redirectToRoute('app_client_dashboard');
        }

        $student = $certifiedRepo->find($selectedStudents[0]);

        if (!$student) {
            $this->addFlash('error', 'Étudiant introuvable');
            return $this->redirectToRoute('app_client_dashboard');
        }

        $date = $template->getCertificateDate()?->format('d/m/Y')
            ?? $student->getGraduationDate()->format('d/m/Y');

        $html = '<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
  body { font-family: Georgia, serif; color: #2c3e6b; background: #f5f3eb; margin: 0; padding: 40px; }
  .diploma { border: 8px solid #a8b8d8; padding: 40px; text-align: center; max-width: 700px; margin: auto; }
  h1 { font-size: 1.6rem; letter-spacing: 3px; text-transform: uppercase; }
  .name { font-size: 2.2rem; font-style: italic; margin: 1rem 0; }
  .footer table { width: 100%; margin-top: 2rem; font-size: 0.85rem; }
  .footer td { text-align: center; padding: 0 10px; }
</style>
</head><body>
<div class="diploma">
  <h1>Diplôme d\'Études Supérieures</h1>
  <p>Le diplôme est remis à</p>
  <div class="name">' . htmlspecialchars($student->getLabel()) . '</div>
  <p>Pour avoir terminé avec succès le programme de ' . htmlspecialchars($template->getSchoolName()) . '.</p>
  <div class="footer">
    <table><tr>
      <td><strong>' . htmlspecialchars($template->getDirectorName()) . '</strong><br>Directeur</td>
      <td>Certifié le : ' . $date . '<br>Identifiant : ' . htmlspecialchars($template->getIdentifier()) . '</td>
      <td><strong>' . htmlspecialchars($template->getAssistantDirectorName()) . '</strong><br>Directeur adjoint</td>
    </tr></table>
  </div>
</div>
</body></html>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'diplome-' . preg_replace('/[^a-zA-Z0-9]/', '-', $student->getLabel()) . '.pdf';

        return new Response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    #[Route('/diplomes/{id}/visualiser', name: 'app_client_diploma_view', methods: ['GET'])]
    public function viewDiploma(int $id, DiplomaRepository $diplomaRepo): Response
    {
        $diploma = $diplomaRepo->find($id);
        if (!$diploma) {
            throw $this->createNotFoundException('Diplôme non trouvé');
        }
        return $this->render('client/diploma/view.html.twig', ['diploma' => $diploma]);
    }

    #[Route('/diplomes/{id}/publier', name: 'app_client_diploma_publish', methods: ['POST'])]
    public function publishDiploma(int $id, DiplomaRepository $diplomaRepo): Response
    {
        $diploma = $diplomaRepo->find($id);
        if (!$diploma) {
            throw $this->createNotFoundException('Diplôme non trouvé');
        }
        $this->addFlash('success', 'Diplôme publié avec succès');
        return $this->redirectToRoute('app_client_diploma_list');
    }
}