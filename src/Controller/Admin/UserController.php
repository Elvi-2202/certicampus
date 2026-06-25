<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users', name: 'app_admin_users')]
class UserController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(UserRepository $repo): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    #[Route('/{id}/toggle-role', name: '_toggle_role', methods: ['POST'])]
    public function toggleRole(
        User $user,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if (
            !$this->isCsrfTokenValid(
                'role'.$user->getId(),
                $request->request->get('_token')
            )
        ) {
            return $this->redirectToRoute('app_admin_users');
        }

        // Empêche de modifier son propre rôle
        if ($user === $this->getUser()) {
            $this->addFlash(
                'danger',
                'Vous ne pouvez pas modifier votre propre rôle.'
            );

            return $this->redirectToRoute('app_admin_users');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles(['ROLE_USER']);
        } else {
            $user->setRoles(['ROLE_ADMIN']);
        }

        $em->flush();

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(
        User $user,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if (
            !$this->isCsrfTokenValid(
                'delete'.$user->getId(),
                $request->request->get('_token')
            )
        ) {
            return $this->redirectToRoute('app_admin_users');
        }

        // Empêche de se supprimer soi-même
        if ($user === $this->getUser()) {
            $this->addFlash(
                'danger',
                'Vous ne pouvez pas supprimer votre propre compte.'
            );

            return $this->redirectToRoute('app_admin_users');
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_users');
    }
}