<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PasswordResetService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository         $userRepository,
        private MailerInterface        $mailer,
        private ValidatorInterface     $validator,
        private string                 $mailerFrom,
        private string                 $mailerFromName,
        private string                 $appUrl,
    ) {}

    public function initiatePasswordReset(string $email): array
    {
        $validation = $this->validateInputData($email);
        if (!$validation['success']) {
            return $validation;
        }

        try {
            $user = $this->userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                return $this->successResponse();
            }

            $this->createPasswordResetToken($user);
            $this->entityManager->flush();
            $this->sendPasswordResetEmail($user);

            return $this->successResponse();

        } catch (\Throwable $e) {
            return [
                'success' => false,
                'code'    => 500,
                'message' => $e->getMessage(), // debug temporaire
            ];
        }
    }

    private function successResponse(): array
    {
        return [
            'success' => true,
            'code'    => 204,
            'message' => 'If the email exists, a reset link has been sent.',
        ];
    }

    private function validateInputData(string $email): array
    {
        $violations = $this->validator->validate($email, new Assert\Email());

        if (count($violations) > 0) {
            return ['success' => false, 'code' => 400, 'message' => 'Invalid email format.'];
        }

        return ['success' => true];
    }

    private function createPasswordResetToken(User $user): void
    {
        $user->setPasswordResetToken(self::generatePasswordResetToken());
        $user->setPasswordResetTokenExpiresAt(new DateTimeImmutable('+24 hours'));
    }

    public static function generatePasswordResetToken(): string
    {
        return bin2hex(random_bytes(64));
    }

    private function sendPasswordResetEmail(User $user): void
    {
        $baseUrl   = rtrim($this->appUrl, '/');
        $resetLink = $baseUrl . '/password-reset/confirm?token=' . $user->getPasswordResetToken();
        $fraudLink = $baseUrl . '/password-reset/report-fraud?email=' . urlencode($user->getEmail());

        $email = (new TemplatedEmail())
            ->from(new Address($this->mailerFrom, $this->mailerFromName))
            ->to(new Address($user->getEmail()))
            ->subject('Réinitialisation de votre mot de passe — ' . $this->mailerFromName)
            // ── template dédié à l'email, séparé du template de la page web ──
            ->htmlTemplate('school/email/password_reset.html.twig')
            ->context([
                'user'             => $user,
                'reset_link'       => $resetLink,
                'fraud_link'       => $fraudLink,
                'app_name'         => $this->mailerFromName,
                'expiration_hours' => 24,
            ]);

        $this->mailer->send($email);
    }
}