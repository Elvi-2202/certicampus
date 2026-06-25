<?php

namespace App\Controller\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordResetConfirmController extends AbstractController
{
    #[Route('/password-reset/confirm', name: 'app_password_reset_confirm', methods: ['GET', 'POST'])]
    public function confirm(
        Request $request,
        UserRepository $userRepo,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
    ): Response {
        $token = $request->query->get('token');

        if (!$token) {
            $this->addFlash('danger', 'Lien de réinitialisation invalide.');
            return $this->redirectToRoute('app_password_reset');
        }

        $user = $userRepo->findOneBy(['password_reset_token' => $token]);

        if (!$user || $user->getPasswordResetTokenExpiresAt() < new \DateTimeImmutable()) {
            $this->addFlash('danger', 'Ce lien a expiré ou est invalide. Veuillez refaire une demande.');
            return $this->redirectToRoute('app_password_reset');
        }

        if ($request->isMethod('GET')) {
            return $this->render('security/password_reset_confirm.html.twig', [
                'token'  => $token,
                'errors' => [],
            ]);
        }

        // POST — validation complète
        $newPassword     = $request->request->get('password', '');
        $confirmPassword = $request->request->get('password_confirm', '');

        $errors = [];

        if (strlen($newPassword) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        if (!preg_match('/[a-zA-Z]/', $newPassword)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre (a-z ou A-Z).';
        }
        if (!preg_match('/[0-9]/', $newPassword)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre (0-9).';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            return $this->render('security/password_reset_confirm.html.twig', [
                'token'  => $token,
                'errors' => $errors,
            ]);
        }

        $user->setPassword($hasher->hashPassword($user, $newPassword));
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenExpiresAt(null);
        $em->flush();

        $this->addFlash('success', 'Mot de passe mis à jour.');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/password-reset/report-fraud', name: 'app_password_reset_fraud', methods: ['GET'])]
    public function reportFraud(Request $request): Response
    {
        return $this->render('security/password_reset_fraud.html.twig', [
            'email' => $request->query->get('email', ''),
        ]);
    }
}