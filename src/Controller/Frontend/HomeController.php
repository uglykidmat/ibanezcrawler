<?php

namespace App\Controller\Frontend;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_welcome')]
    public function index()
    {
        $pageTitle = 'Home ! IBZCRWLR';

        return $this->render('home.html.twig', [
            'page_title' => $pageTitle
        ]);
    }
}