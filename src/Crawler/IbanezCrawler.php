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
        $crawler = new Crawler($crawlResponse);

        // $titleNode = $crawler->filterXPath("body//span[@class='mw-page-title-main']");

        // foreach ($titleNode as $key => $value) {
        //     var_dump($value->nodeValue);
        // }

        $title = $crawler->filter('span.mw-page-title-main')->text();
        var_dump('MODÈLE : ' . $title);
        // echo ('<br/>');

        $description = $crawler->filter('div.mw-parser-output')->text();
        var_dump('Description : ' . $description);

        $classesMet = [];
        $crawler->filter('span')->each(function (Crawler $node, $i) {
            var_dump($i, $node->text());
            //dd($node->text());
            $classesMet[] = $node->text();
            //$classesMet[] = $node->attr('class', 'pop');
        });

        dd($classesMet);

        // $divNodes = $crawler->filterXPath('descendant-or-self::body/div');

        // foreach ($divNodes as $key => $value) {
        //     var_dump($value->nodeValue);
        // }

        //______________________________________
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
