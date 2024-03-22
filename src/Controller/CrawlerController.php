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
    public function getOneGuitar()
    {
        $url = 'https://ibanez.fandom.com/wiki/GRG270B';

        return $this->json(
            $this->guitarCrawler->crawlOneGuitar($url)
        );
    }

    #[Route('/crawler/{serie}', name: 'app_crawler_by_serie')]
    public function getBySerie(string $serie): JsonResponse
    {
        return $this->json(
            $this->guitarCrawler->crawlGuitarCategory($serie)
        );
    }
}
