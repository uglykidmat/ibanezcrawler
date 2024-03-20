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
        $crawlContent = $this->ibanezCrawler->crawl()->getContent();
        dd($crawlContent);
        return $this->json([
            'data' => $crawlContent,
            //'path' => 'src/Controller/CrawlerController.php',
        ]);
    }
}
