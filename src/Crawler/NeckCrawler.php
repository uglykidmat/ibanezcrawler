<?php

namespace App\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NeckCrawler
{
    public function __construct(
        public HttpClientInterface $client,
    ) {
        $this->client = $client->withOptions([]);
    }

    public function crawlGuitarNecks(): array
    {
        $url = 'https://ibanez.fandom.com/wiki/List_of_neck_types';
        $response = $this->client->request('GET', $url)->getContent();

        //____________________CRAWLER
        $crawler = new Crawler($response);

        //____________________CRAWL-TITLES
        $dataTitles = [];
        $guitarNecksTableTitles = $crawler->filterXPath('//table[contains(@class,"wikitable")]/tbody/tr/th');

        foreach ($guitarNecksTableTitles as $titleNode) {
            $dataTitles[] = trim($titleNode->textContent);
        }

        //____________________CRAWL-NECKS
        $guitarNecksTable = $crawler->filterXPath('//table[contains(@class,"wikitable")]//tr/td');
        dd($dataTitles);
        dd($guitarNecksTable);
        return [];
    }
}
