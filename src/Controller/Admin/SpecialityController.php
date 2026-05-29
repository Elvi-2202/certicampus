<?php
namespace App\Controller\Admin;

use App\Entity\Speciality;
use App\Form\SpecialityType;
use App\Repository\SpecialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/specialities', name: 'app_admin_specialities')]
class SpecialityController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(SpecialityRepository $repo): Response
    {
        return $this->render('admin/specialities/index.html.twig', [
            'specialities' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $speciality = new Speciality();
        $form = $this->createForm(SpecialityType::class, $speciality);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($speciality);
            $em->flush();
            return $this->redirectToRoute('app_admin_specialities');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Nouvelle spécialité',
            'active'   => 'specialities',
            'back_url' => $this->generateUrl('app_admin_specialities'),
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Speciality $speciality, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SpecialityType::class, $speciality);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_admin_specialities');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Modifier ' . $speciality->getLabel(),
            'active'   => 'specialities',
            'back_url' => $this->generateUrl('app_admin_specialities'),
        ]);
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Speciality $speciality, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $speciality->getId(), $request->request->get('_token'))) {
            $em->remove($speciality);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_specialities');
    }
}
