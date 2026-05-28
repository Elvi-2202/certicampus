<?php
namespace App\Controller\Admin;

use App\Entity\Diploma;
use App\Form\DiplomaType;
use App\Repository\DiplomaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/diplomas', name: 'app_admin_diplomas')]
class DiplomaController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(DiplomaRepository $repo): Response
    {
        return $this->render('admin/diplomas/index.html.twig', [
            'diplomas' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $diploma = new Diploma();
        $form = $this->createForm(DiplomaType::class, $diploma);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($diploma);
            $em->flush();
            return $this->redirectToRoute('app_admin_diplomas');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Nouveau diplôme',
            'active'   => 'diplomas',
            'back_url' => $this->generateUrl('app_admin_diplomas'),
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Diploma $diploma, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DiplomaType::class, $diploma);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_admin_diplomas');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Modifier diplôme #' . $diploma->getId(),
            'active'   => 'diplomas',
            'back_url' => $this->generateUrl('app_admin_diplomas'),
        ]);
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Diploma $diploma, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $diploma->getId(), $request->request->get('_token'))) {
            $em->remove($diploma);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_diplomas');
    }
}
