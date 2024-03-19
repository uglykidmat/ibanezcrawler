<?php

namespace App\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IbanezCrawler
{
    public function __construct(
        public HttpClientInterface $client,
    ) {
        $this->client = $client->withOptions([]);
    }

    public function crawl(): JsonResponse
    {
        $url = 'https://ibanez.fandom.com/wiki/GRG270B';

        $crawlResponse = $this->client->request('GET', $url)->getContent();
        //dd($crawlResponse);

        //div.main-container    
        $crawler = new Crawler($crawlResponse);
        //var_dump($crawler);
        // foreach ($crawler as $key => $value) {
        //     var_dump($value->nodeValue);

        echo ('🤡🤡🤡');
        // }

        $divNodes = $crawler->filterXPath('descendant-or-self::body/div');

        foreach ($divNodes as $key => $value) {
            var_dump($value->nodeValue);
        }
        // var_dump($divNodes->getNode(0)->nodeName);
        // var_dump($divNodes->getNode(0)->nodeType);
        // var_dump($divNodes->getNode(0)->nodeValue);

        // $bodyNode = $crawler->filterXPath('child::node()');
        // var_dump($bodyNode);

        // $allnodes = [];
        // foreach ($crawler as $domElement) {
        //     //$allnodes[] = $domElement->nodeName;
        //     echo ('🤡');
        //     var_dump($domElement->nodeName);
        // }
        //dd($allnodes);

        $output = new JsonResponse();
        $output->setContent('ok');
        return $output;
    }

    public function getIbanezData()
    {
        //$request = new HTTPCli
    }
}
