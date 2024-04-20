<?php

namespace App\Controller;

use App\Entity\Guitar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuitarController extends AbstractController
{
    public function __construct(
        public EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/guitar/{family}', name: 'guitar_by_family')]
    public function getByFamily(string $family): JsonResponse
    {
        $family = strlen($family) > 3 ? ucfirst($family) : strtoupper($family);
        $allGuitarsByFamily = $this->entityManager->getRepository(Guitar::class)->findByFamily($family);

        $response = [];

        foreach ($allGuitarsByFamily as $guitar) {
            //dd($guitar);
            $response[] = [
                'model' => $guitar->getModel(),
                'body type' => $guitar->getBodytype(),
                'body material' => $guitar->getBodymaterial(),
                'neck type' => $guitar->getNecktype(),
                'neck joint' => $guitar->getNeckjoint(),
                'finishes' => $guitar->getFinishes(),
            ];
        }
        //dd($response);

        return $this->json([
            'info' => 'Success',
            'data' => $response,
        ]);
    }
}
