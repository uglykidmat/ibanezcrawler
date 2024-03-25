<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Crawler\GuitarCrawler;
use App\Crawler\NeckCrawler;

class CrawlerController extends AbstractController
{
    public function __construct(
        private GuitarCrawler $guitarCrawler,
        private NeckCrawler $neckCrawler
    ) {
    }

    #[Route('/', name: 'app_welcome')]
    public function index()
    {

        return $this->json(
            [
                'URLs' =>
                [
                    'Crawl one guitar (example/test)' => '/crawler/guitar',
                    'Crawl guitars by model (S, RG, etc)' => '/crawler/guitars/{serie}',
                    'Crawl guitar necks' => '/crawler/guitarnecks',
                ]
            ]
        );
    }

    #[Route('/crawler/guitar', name: 'app_crawler')]
    public function getOneGuitar()
    {
        $url = 'https://ibanez.fandom.com/wiki/GRG270B';

        return $this->json(
            $this->guitarCrawler->crawlOneGuitar($url)
        );
    }

    #[Route('/crawler/guitars/{serie}', name: 'crawler_guitars_by_serie')]
    public function getBySerie(string $serie): JsonResponse
    {
        set_time_limit(0);
        return $this->json(
            $this->guitarCrawler->crawlGuitarCategory($serie)
        );
    }

    #[Route('/crawler/guitarnecks', name: 'crawler_guitar_necks')]
    public function getGuitarNeck(): JsonResponse
    {
        return $this->json(
            $this->neckCrawler->crawlGuitarNecks()
        );
    }
}
