<?php

namespace App\Controller\Frontend;

use App\Entity\Guitar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $pageTitle = 'Dashboard ! IBZCRWLR';

        $guitarRepo = $this->entityManager->getRepository(Guitar::class);
        $totalGuitars = $guitarRepo->count([]);
        $totalDifferentFamilies = $guitarRepo->countFamilies();
        return $this->render('dashboard/dashboard.html.twig', [
            'page_title' => $pageTitle,
            'total_guitars' => $totalGuitars,
            'totaldifferentfamilies' => $totalDifferentFamilies
        ]);
    }
}