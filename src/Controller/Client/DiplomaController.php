<?php

namespace App\Controller\Client;

use App\Repository\DiplomaRepository;
use App\Repository\SpecialityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client/diplomes')]
final class DiplomaController extends AbstractController
{
    #[Route('', name: 'app_client_diploma_list', methods: ['GET'])]
    public function index(
        Request $request,
        DiplomaRepository $diplomaRepo,
        SpecialityRepository $specRepo
    ): Response
    {
        $specialityId = $request->query->get('speciality');
        
        if ($specialityId) {
            $diplomas = $diplomaRepo->findBy(['speciality' => $specialityId]);
        } else {
            $diplomas = $diplomaRepo->findAll();
        }

        return $this->render('client/diploma/index.html.twig', [
            'diplomas' => $diplomas,
            'specialities' => $specRepo->findAll(),
        ]);
    }
}