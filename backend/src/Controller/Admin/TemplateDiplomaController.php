<?php
namespace App\Controller\Admin;

use App\Entity\TemplateDiploma;
use App\Form\TemplateDiplomaType;
use App\Repository\TemplateDiplomaRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            $em->persist($template);
            $em->flush();
            return $this->redirectToRoute('app_admin_template_diplomas');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Nouveau template',
            'active'   => 'templates',
            'back_url' => $this->generateUrl('app_admin_template_diplomas'),
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
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Modifier ' . $template->getLabel(),
            'active'   => 'templates',
            'back_url' => $this->generateUrl('app_admin_template_diplomas'),
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
}
