<?php

namespace App\Controller\School;

use App\Service\PasswordResetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/school')]
class PasswordResetController extends AbstractController
{
    public function __construct(
        private PasswordResetService $passwordResetService,
    ) {
    }

    #[Route('/password-reset', name: 'api_school_password_reset', methods: ['POST'])]
    public function passwordReset(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Validate JSON was parsed
            if ($data === null) {
                return new JsonResponse([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Invalid JSON in request body.'
                ], 400);
            }

            // Validate required fields
            if (!isset($data['email']) || empty($data['email'])) {
                return new JsonResponse([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Email is required.'
                ], 400);
            }

            // Initiate password reset
            $result = $this->passwordResetService->initiatePasswordReset($data['email']);

            // Return response with appropriate HTTP status
            return new JsonResponse(
                [
                    'success' => $result['success'],
                    'code' => $result['code'],
                    'message' => $result['message']
                ],
                $result['code']
            );
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'code' => 500,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }
}
