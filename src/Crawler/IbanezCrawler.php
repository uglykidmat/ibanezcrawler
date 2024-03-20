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

    public function crawl(): string
    {
        //____________________URL
        $url = 'https://ibanez.fandom.com/wiki/GRG270B';

        //____________________CLIENT
        $crawlResponse = $this->client->request('GET', $url)->getContent();

        //____________________CRAWLER
        $crawler = new Crawler($crawlResponse);

        //____________________CRAWL-TITLE
        $title = $crawler->filterXPath("//span[@class='mw-page-title-main']")->text();

        // $title = $crawler->filter('span.mw-page-title-main')->text();

        //____________________CRAWL-DESCRIPTION
        $description = '';
        $descriptionParagraphs = $crawler->filterXPath('descendant-or-self::div[@class="mw-parser-output"]//p');

        foreach ($descriptionParagraphs as $paragraph) {
            $description .= $paragraph->textContent;
        }

        //____________________________________________--
        // $classesMet = [];
        // $crawler->filter('span')->each(function (Crawler $node, $i) {
        //     var_dump($i, $node->text());
        //     //dd($node->text());
        //     $classesMet[] = $node->text();
        //     //$classesMet[] = $node->attr('class', 'pop');
        // });

        // dd($classesMet);
        //____________________________________________--
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

        //____________________CRAWL-OUTPUT
        $outputContent = json_encode(['title' => $title, 'description' => $description]);

        // $output = new JsonResponse();
        // $output->setContent($outputContent);

        return $outputContent;
    }

    public function getIbanezData()
    {
        //$request = new HTTPCli
    }
}
