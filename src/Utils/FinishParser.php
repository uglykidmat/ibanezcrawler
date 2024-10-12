<?php

namespace App\Crawler\Utils;

use App\Entity\Finish;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class FinishParser
{
    public function __construct(
        public HttpClientInterface $client,
        private EntityManagerInterface $entityManager
    ) {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function checkFinishes()
    {
        self::parseFinishes();

        return null;
    }
    public function parseFinishes()
    {
        $finishesURL = 'https://ibanez.fandom.com/wiki/List_of_finishes';
        $response = $this->client->request('GET', $finishesURL)->getContent();

        dd('HOLA');
        //____________________CRAWLER
        $crawler = new Crawler($response);
    }
    protected function compareToDB()
    {
    }
    protected function addToDB()
    {
    }
}
// 