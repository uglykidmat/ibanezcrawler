<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Crawler\IbanezCrawler;

class CrawlerController extends AbstractController
{
    #[Route('/crawler', name: 'app_crawler')]
    public function index(): JsonResponse
    {
        $crawler = new IbanezCrawler();
        dd($crawler->crawl());

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CrawlerController.php',
        ]);
    }
}
