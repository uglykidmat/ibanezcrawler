<?php

namespace App\Controller;

use App\Crawler\GuitarCrawler;
use App\Crawler\NeckCrawler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CrawlerController extends AbstractController
{
    public function __construct(
        private GuitarCrawler $guitarCrawler,
        private NeckCrawler $neckCrawler,
    ) {
    }

    // #[Route('/', name: 'app_welcome')]
    // public function index()
    // {
    //     $pageTitle = 'IBZCRWLR Homepage';
    //     return $this->render('home.html.twig', [
    //         'page_title' => $pageTitle
    //     ]);
    // }

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
                    'results' => $SerieResponse,
                ]
            );
        } else {
            return $this->json(
                [
                    'info' => 'fail',
                    'results' => 'none',
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
