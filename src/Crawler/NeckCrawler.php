<?php

namespace App\Crawler;

use App\Entity\Neck;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NeckCrawler
{
    public function __construct(
        public HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
    ) {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function crawlGuitarNecks(): array
    {
        $url = 'https://ibanez.fandom.com/wiki/List_of_neck_types';
        $response = $this->client->request('GET', $url)->getContent();

        // ____________________CRAWLER
        $crawler = new Crawler($response);

        // ____________________CRAWL-TITLES
        $tableTitles = $crawler->filterXPath('//table[contains(@class,"wikitable")]/tbody/tr/th');

        foreach ($tableTitles as &$titleNode) {
            $dataTitles[] = trim($titleNode->textContent);
        }
        unset($titleNode);

        // ____________________CRAWL WIKI TABLE
        $allTable = $crawler->filterXPath('//table[contains(@class,"wikitable")]//tr');
        foreach ($allTable as &$node) {
            if (
                preg_match('/\\n\\n\\n/', $node->textContent)
            ) {
                $nodes[] = trim(preg_replace('/\\n\\n\\n/', PHP_EOL.PHP_EOL.'—'.PHP_EOL.PHP_EOL, $node->textContent));
            } else {
                $nodes[] = trim($node->textContent);
            }
        }
        unset($node);

        // ____________________REMOVE TITLES
        array_shift($nodes);

        // ____________________TURN STRINGS INTO ARRAYS
        foreach ($nodes as &$node) {
            $guitarNecks[] = array_values(array_filter(explode(PHP_EOL, $node)));
        }
        unset($node);

        // ____________________PROPER NECK PARSING AND REARRANGING
        foreach ($guitarNecks as $key => &$neck) {
            // __remove [links]
            $neck[0] = preg_replace('/\[\d+\]/', '', $neck[0]);
            $neck[1] = preg_replace('/\[\d+\]/', '', $neck[1]);
            if (preg_match('/\d{4}-\d{4}|(?<!\W)(?<!\w)\d{4}/', $neck[0])) {
                array_unshift($neck, $guitarNecks[$key - 1][0]);
            }
        }
        unset($neck);

        // ____________________MERGE WITH TITLES

        // ____________________MERGE WITH TITLES
        foreach ($guitarNecks as $i => &$neck) {
            $finalNecks[] = array_combine($dataTitles, $neck);
        }

        file_put_contents(__DIR__.'/../../public/data/necks.json', json_encode($finalNecks, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $finalNecks;
    }

    public function addNecksToDb(): int
    {
        $count = 0;
        $necks = json_decode(file_get_contents(__DIR__.'/../../public/data/necks.json'), true);
        foreach ($necks as $neck) {
            $neckEntity = new Neck();
            $neckEntity->setType($neck['Neck type'])
                ->setYears($neck['Years'])
                ->setScaleLength($neck['Scale length'])
                ->setWidthAtNut($neck['Width at nut'])
                ->setWidthAtLastFret($neck['Width at last fret'])
                ->setThicknessAt1stFret($neck['Thickness at 1st fret'])
                ->setThicknessAt12thFret($neck['Thickness at 12th fret'])
                ->setRadius($neck['Radius']);

            $this->entityManager->persist($neckEntity);
            ++$count;
        }
        $this->entityManager->flush();

        return $count;
    }

    public function purgeGuitarNecks(): int
    {
        $count = 0;
        $neckRepository = $this->entityManager->getRepository(Neck::class);

        foreach ($neckRepository->findAll() as $neck) {
            $this->entityManager->remove($neck);
            ++$count;
        }
        $this->entityManager->flush();

        return $count;
    }
}
