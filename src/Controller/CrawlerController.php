<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Crawler\IbanezCrawler;


class CrawlerController extends AbstractController
{
    public function __construct(
        private IbanezCrawler $ibanezCrawler
    ) {
    }

    #[Route('/crawler', name: 'app_crawler')]
    public function index(): JsonResponse
    {
        //$crawler = new IbanezCrawler();
        dd($this->ibanezCrawler->crawl());

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CrawlerController.php',
        ]);
    }
}
