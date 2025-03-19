<?php

namespace App\Controller\Frontend;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuitarSearchController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/guitarsearch', name: 'app_guitarsearch')]
    public function index(): Response
    {
        $pageTitle = 'Search ! IBZCRWLR';


        return $this->render('search/guitarsearch.html.twig', [
            'page_title' => $pageTitle,
        ]);
    }
}