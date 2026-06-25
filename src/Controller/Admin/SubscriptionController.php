<?php

namespace App\Controller\Admin;

use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\School;

#[Route('/admin/subscriptions', name: 'app_admin_subscriptions')]
final class SubscriptionController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(SubscriptionRepository $repo, EntityManagerInterface $em): Response
    {
        return $this->render('admin/subscription/index.html.twig', [
            'subscriptions' => $repo->findAll(),
            'schools' => $em->getRepository(School::class)->findAll(),
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subscription);
            $em->flush();
            $this->addFlash('success', 'Abonnement créé avec succès.');
            return $this->redirectToRoute('app_admin_subscriptions');
        }

        return $this->render('admin/subscription/form.html.twig', [
            'form' => $form,
            'title' => 'Nouvel abonnement',
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Subscription $subscription, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SubscriptionType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Abonnement modifié avec succès.');
            return $this->redirectToRoute('app_admin_subscriptions');
        }

        return $this->render('admin/subscription/form.html.twig', [
            'form' => $form,
            'title' => 'Modifier l\'abonnement',
        ]);
    }

    #[Route('/delete', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, SubscriptionRepository $repo, EntityManagerInterface $em): Response
    {
        $ids = $request->request->all('ids');

        if ($this->isCsrfTokenValid('delete_subscriptions', $request->request->get('_token'))) {
            foreach ($ids as $id) {
                $subscription = $repo->find($id);
                if ($subscription) {
                    $em->remove($subscription);
                }
            }
            $em->flush();
            $this->addFlash('success', count($ids) . ' abonnement(s) supprimé(s).');
        }

        return $this->redirectToRoute('app_admin_subscriptions');
    }

    #[Route('/{id}/assign', name: '_assign', methods: ['POST'])]
    public function assign(Subscription $subscription, Request $request, SubscriptionRepository $repo, EntityManagerInterface $em): Response
    {
        $schoolId = $request->request->get('school_id');
        
        if ($this->isCsrfTokenValid('assign_' . $subscription->getId(), $request->request->get('_token'))) {
            $school = $em->getRepository(School::class)->find($schoolId);
            if ($school) {
                $subscription->setSchool($school);
                $em->flush();
                $this->addFlash('success', 'Abonnement assigné à ' . $school->getLabel());
            }
        }

        return $this->redirectToRoute('app_admin_subscriptions');
    }
}