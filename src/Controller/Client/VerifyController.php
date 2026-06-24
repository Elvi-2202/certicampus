<?php

namespace App\Controller\Client;

use App\Repository\TemplateDiplomaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VerifyController extends AbstractController
{
    #[Route('/verify/{uuid}', name: 'app_verify_certified', methods: ['GET'])]
    public function verify(string $uuid, TemplateDiplomaRepository $templateRepo): Response
    {
        $template = $templateRepo->findOneBy(['identifier' => $uuid]);

        if (!$template) {
            return $this->render('verify/invalid.html.twig');
        }

        return $this->render('verify/valid.html.twig', [
            'template' => $template,
        ]);
    }
}