<?php
namespace App\Controller\Admin;

use App\Entity\TemplateDiploma;
use App\Form\TemplateDiplomaType;
use App\Repository\TemplateDiplomaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/template-diplomas', name: 'app_admin_template_diplomas')]
class TemplateDiplomaController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(TemplateDiplomaRepository $repo): Response
    {
        return $this->render('admin/templates/index.html.twig', [
            'templates' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $template = new TemplateDiploma();
        $form = $this->createForm(TemplateDiplomaType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $certified = $form->get('certified')->getData();
            $template->setIdentifier((string) $certified->getUuid());
            $em->persist($template);
            $em->flush();
            return $this->redirectToRoute('app_admin_template_diplomas');
        }

        return $this->render('admin/templates/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(TemplateDiploma $template, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TemplateDiplomaType::class, $template);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_admin_template_diplomas');
        }
        return $this->render('admin/templates/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(TemplateDiploma $template, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $template->getId(), $request->request->get('_token'))) {
            $em->remove($template);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_template_diplomas');
    }

    #[Route('/{id}/download', name: '_download', methods: ['GET'])]
    public function download(TemplateDiploma $template): Response
    {
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