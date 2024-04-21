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

    #[Route('/guitars/{family}', name: 'guitars_by_family')]
    public function getAllGuitarsByFamily(string $family): JsonResponse
    {
        $family = strlen($family) > 3 ? ucfirst($family) : strtoupper($family);
        if ($allGuitarsByFamily = $this->entityManager->getRepository(Guitar::class)->findByFamily($family)) {
            $response = [];
            foreach ($allGuitarsByFamily as $guitar) {
                preg_match_all('/[A-Z]{2}/', $guitar->getFinishes(), $finishesCodes);
                $response[] = [
                    'model name' => $guitar->getModelName(),
                    'body type' => $guitar->getBodytype(),
                    'body material' => $guitar->getBodymaterial(),
                    'neck type' => $guitar->getNecktype(),
                    'neck joint' => $guitar->getNeckjoint(),
                    'finishes' => $guitar->getFinishes(),
                    'finishes codes' => $finishesCodes[0],
                    'years offered' => $guitar->getYearsoffered(),
                    'extra info' => $guitar->getExtraParenthesisInfo(),
                ];
            }

            return $this->json([
                'info' => 'Success',
                'data' => $response,
            ]);
        } else {
            return $this->json([
                'info' => 'Failure',
                'data' => 'No models found !',
            ]);
        }
    }

    #[Route('/guitar/{model}', name: 'guitar_by_model')]
    public function getGuitarModel(string $model): JsonResponse
    {
        $response = [];
        if ($guitarByModel = $this->entityManager->getRepository(Guitar::class)->findByModelName($model)) {
            foreach ($guitarByModel as $guitar) {
                preg_match_all('/[A-Z]{2}/', $guitar->getFinishes(), $finishesCodes);
                $response[] = [
                    'model name' => $guitar->getModelName(),
                    'body type' => $guitar->getBodytype(),
                    'body material' => $guitar->getBodymaterial(),
                    'neck type' => $guitar->getNecktype(),
                    'neck joint' => $guitar->getNeckjoint(),
                    'finishes' => $guitar->getFinishes(),
                    'finishes codes' => $finishesCodes[0],
                    'years offered' => $guitar->getYearsoffered(),
                    'extra info' => $guitar->getExtraParenthesisInfo(),
                ];
            }

            return $this->json([
                'info' => 'Success',
                'data' => $response,
            ]);
        } else {
            return $this->json([
                'info' => 'Failure',
                'data' => 'No model found !',
            ]);
        }
    }
}
