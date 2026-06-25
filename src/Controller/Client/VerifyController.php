<?php

namespace App\Controller\Client;

use App\Repository\TemplateDiplomaRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VerifyController extends AbstractController
{
    #[Route('/verify/{uuid}', name: 'app_verify_certified', methods: ['GET'])]
    public function verify(string $uuid, TemplateDiplomaRepository $templateRepo): Response
    {
        $template = $templateRepo->findOneBy(['identifier' => $uuid]);

        if (!$template) {
            return $this->render('verify/invalid.html.twig');
        }

        return $this->render('verify/valid.html.twig', [
            'template' => $template,
        ]);
    }

    #[Route('/verify/{uuid}/download', name: 'app_verify_download', methods: ['GET'])]
    public function download(string $uuid, TemplateDiplomaRepository $templateRepo): Response
    {
        $template = $templateRepo->findOneBy(['identifier' => $uuid]);

        if (!$template) {
            throw $this->createNotFoundException('Diplôme introuvable');
        }

        $date = $template->getCertificateDate()?->format('d/m/Y') ?? '';

        $html = '<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
  body { font-family: Georgia, serif; color: #2c3e6b; background: #f5f3eb; margin: 0; padding: 40px; }
  .diploma { border: 8px solid #a8b8d8; padding: 40px; text-align: center; max-width: 700px; margin: auto; }
  h1 { font-size: 1.6rem; letter-spacing: 3px; text-transform: uppercase; }
  .name { font-size: 2.2rem; font-style: italic; margin: 1rem 0; }
  .footer { margin-top: 2rem; font-size: 0.85rem; }
  .footer table { width: 100%; }
  .footer td { text-align: center; padding: 0 10px; }
</style>
</head><body>
<div class="diploma">
  <h1>Diplôme d\'Études Supérieures</h1>
  <p>Le diplôme est remis à</p>
  <div class="name">' . htmlspecialchars($template->getStudentName()) . '</div>
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

        $filename = 'diplome-' . preg_replace('/[^a-zA-Z0-9]/', '-', $template->getStudentName()) . '.pdf';

        return new Response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}