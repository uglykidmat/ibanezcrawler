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
        $tableTitles = $crawler->filterXPath('//table[contains(@class,"wikitable")]/tbody/tr/th');

        foreach ($tableTitles as &$titleNode) {
            $dataTitles[] = trim($titleNode->textContent);
        }

        //____________________CRAWL WIKI TABLE
        $allTable = $crawler->filterXPath('//table[contains(@class,"wikitable")]//tr');
        foreach ($allTable as &$node) {

            if (
                preg_match('/\\n\\n\\n/', $node->textContent)
            ) {
                $nodes[] = trim(preg_replace('/\\n\\n\\n/', PHP_EOL . PHP_EOL . '—' . PHP_EOL . PHP_EOL, $node->textContent));
                //dd($node->textContent);
            } else {
                $nodes[] = trim($node->textContent);
            }
        }
        unset($node);

        //____________________REMOVE TITLES
        array_shift($nodes);
        //dd($nodes);
        //____________________TURN STRINGS INTO ARRAYS
        foreach ($nodes as &$node) {
            $guitarNecks[] = array_values(array_filter(explode(PHP_EOL, $node)));
        }
        unset($node);

        //____________________PROPER NECK PARSING AND REARRANGING
        foreach ($guitarNecks as $key => &$neck) {
            //__remove [links]
            $neck[0] = preg_replace('/\[\d+\]/', '', $neck[0]);
            // if ($neck[$key] == ' ') {
            //     dd($neck[$key]);
            //     $neck[$key] = '—';
            // }
            if (preg_match('/((\d\d\d\d)–(\d\d\d\d))|(\d\d\d\d)/', $neck[0])) {
                array_unshift($neck, $guitarNecks[$key - 1][0]);
            }
        }
        unset($neck);
        //dd($dataTitles);

        //____________________MERGE WITH TITLES
        foreach ($guitarNecks as $neck) {
            //$finalNecks = array_combine($dataTitles, $neck);
            //dd($finalNecks);
            if (count($dataTitles) != count($neck)) {
                dd($neck);
            }
            $finalNecks[] = array_combine($dataTitles, $neck);
        }

        return $finalNecks;
    }
}
