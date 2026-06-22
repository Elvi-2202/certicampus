<?php

namespace App\Controller\Admin;

use App\Entity\Certified;
use App\Form\CertifiedType;
use App\Repository\CertifiedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/certified', name: 'app_admin_certified')]
#[IsGranted('ROLE_ADMIN')]
class CertifiedController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(CertifiedRepository $repo): Response
    {
        return $this->render('admin/certified/index.html.twig', [
            'certifieds' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $certified = new Certified();
        $form = $this->createForm(CertifiedType::class, $certified);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($certified);
            $em->flush();
            $this->addFlash('success', 'Certifié ajouté avec succès.');
            return $this->redirectToRoute('app_admin_certified');
        }

        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Nouveau certifié',
            'active'   => 'certified',
            'back_url' => $this->generateUrl('app_admin_certified'),
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Certified $certified, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CertifiedType::class, $certified);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Certifié modifié avec succès.');
            return $this->redirectToRoute('app_admin_certified');
        }

        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Modifier ' . $certified->getLabel(),
            'active'   => 'certified',
            'back_url' => $this->generateUrl('app_admin_certified'),
        ]);
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Certified $certified, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $certified->getId(), $request->request->get('_token'))) {
            $em->remove($certified);
            $em->flush();
            $this->addFlash('success', 'Certifié supprimé.');
        }

        return $this->redirectToRoute('app_admin_certified');
    }
}