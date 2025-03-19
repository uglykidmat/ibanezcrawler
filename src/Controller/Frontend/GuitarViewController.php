<?php

namespace App\Controller\Frontend;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuitarViewController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/guitarview', name: 'app_guitarview')]
    public function index()
    {
        $pageTitle = 'View ! IBZCRWLR';

        return $this->render('guitar/guitarview.html.twig', [
            'page_title' => $pageTitle
        ]);
    }
}