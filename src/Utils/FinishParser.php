<?php

namespace App\Utils;

use App\Entity\Finish;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FinishParser
{
    public function __construct(
        public HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
    ) {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function checkFinishes()
    {
        $this->compareToDB($this->parseFinishes());

        return null;
    }

    protected function parseFinishes(): array
    {
        $finishesURL = 'https://ibanez.fandom.com/wiki/List_of_finishes';
        $response = $this->client->request('GET', $finishesURL)->getContent();
        echo '🎨 Also checking for new finishes at https://ibanez.fandom.com/wiki/List_of_finishes...' . PHP_EOL;
        // ____________________CRAWLER
        $crawler = new Crawler($response);
        $finishesTable = $crawler->filterXPath("//table[@class='viewstable']/tbody//tr");
        $finishesParsedTable = [];
        foreach ($finishesTable as $finish) {
            preg_match('/(.*)\n+(.*)/', trim($finish->nodeValue), $matches);
            $finishesParsedTable[] = [
                'shortname' => $matches[1],
                'name' => $matches[2],
            ];
        }

        return $finishesParsedTable;
    }

    protected function compareToDB(array $finishesParsedTable)
    {
        $finishRepository = $this->entityManager->getRepository(Finish::class);
        if (count($finishesParsedTable) !== count($finishRepository->findAll())) {
            foreach ($finishRepository as $finishInDB) {
                $finishInDB->remove();
            }
            foreach ($finishesParsedTable as $finishToAdd) {
                $newFinish = new Finish();
                $newFinish->setShortName($finishToAdd['shortname'])->setName($finishToAdd['name']);
                $this->entityManager->persist($newFinish);
                echo '🎨 Add new finish "' . $finishToAdd['name'] . '"' . PHP_EOL;
            }
            $this->entityManager->flush();
        } else {
            echo '🎨 ...nah, the whole spectrum is here.' . PHP_EOL;
        }

        return true;
    }
}
