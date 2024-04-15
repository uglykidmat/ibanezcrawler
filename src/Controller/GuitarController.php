<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GuitarController extends AbstractController
{
    #[Route('/guitar/{family}', name: 'guitar_by_family')]
    public function getFamily(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GuitarController.php',
        ]);
    }
}
