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
                'Crawler URLs' =>
                [
                    'Crawl one guitar (example/test)' => '/crawler/guitar',
                    'Crawl guitars by model (S, RG, Prestige, etc)' => '/crawler/guitars/{serie}',
                    'Crawl guitar necks' => '/crawler/guitarnecks',
                ],
                'Info URLs' =>
                [
                    'See a specific model' => '/guitar/{model}',
                    'See a specific family (S, RG, Prestige, etc)' => '/guitars/{serie}',
                ]
            ]
        );
    }

    #[Route('/crawler/guitars/{serie}', name: 'crawler_guitars_by_serie')]
    public function getBySerie(string $serie): JsonResponse
    {
        ob_start();
        set_time_limit(0);
        $serie = strlen($serie) > 3 ? ucfirst($serie) : strtoupper($serie);
        $SerieResponse = $this->guitarCrawler->crawlGuitarCategory($serie);
        if (ob_end_clean()) {
            return $this->json(
                [
                    'info' => 'Here are the results, but the JSON file has not been created/updated. Use app:guitarcrawler [Serie] for this.',
                    'results' => $SerieResponse
                ]
            );
        }
    }

    #[Route('/crawler/guitarnecks', name: 'crawler_guitar_necks')]
    public function getGuitarNeck(): JsonResponse
    {
        return $this->json(
            $this->neckCrawler->crawlGuitarNecks()
        );
    }
}
