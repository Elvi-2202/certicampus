<?php
namespace App\Controller\Admin;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/trainings', name: 'app_admin_trainings')]
class TrainingController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(TrainingRepository $repo): Response
    {
        return $this->render('admin/trainings/index.html.twig', [
            'trainings' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($training);
            $em->flush();
            return $this->redirectToRoute('app_admin_trainings');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Nouvelle formation',
            'active'   => 'trainings',
            'back_url' => $this->generateUrl('app_admin_trainings'),
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Training $training, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_admin_trainings');
        }
        return $this->render('admin/form.html.twig', [
            'form'     => $form,
            'title'    => 'Modifier ' . $training->getLabel(),
            'active'   => 'trainings',
            'back_url' => $this->generateUrl('app_admin_trainings'),
        ]);
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Training $training, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $training->getId(), $request->request->get('_token'))) {
            $em->remove($training);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_trainings');
    }
}
