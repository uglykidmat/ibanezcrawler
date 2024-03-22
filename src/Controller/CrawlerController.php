<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Crawler\GuitarCrawler;


class CrawlerController extends AbstractController
{
    public function __construct(
        private GuitarCrawler $guitarCrawler
    ) {
    }

    #[Route('/crawler', name: 'app_crawler')]
    public function index(): JsonResponse
    {
        $url = 'https://ibanez.fandom.com/wiki/GRG270B';
        $crawlContent = $this->guitarCrawler->crawlOneGuitar($url);
        $resp = new JsonResponse();
        $resp->setContent($crawlContent);

        return $resp;
    }
}
