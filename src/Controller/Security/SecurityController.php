<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/password-reset', name: 'app_password_reset', methods: ['GET'])]
    public function passwordReset(): Response
    {
        return $this->render('security/password_reset.html.twig', [
            'success' => false,
            'error' => null,
        ]);
    }
}
