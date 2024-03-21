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
        $model = $crawler->filterXPath("//span[@class='mw-page-title-main']")->text();

        //____________________CRAWL-DESCRIPTION
        $description = '';
        $descriptionParagraphs = $crawler->filterXPath('descendant-or-self::div[@class="mw-parser-output"]//p');

        foreach ($descriptionParagraphs as $paragraph) {
            $description .= $paragraph->textContent;
        }

        //____________________CRAWL-DETAILS

        $bodySpecs = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[1]/table/tbody//td');
        $neckSpecs = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[2]/table/tbody//td');
        $electronicsAndStringsSpecs = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[3]/table/tbody//td');

        $bodySpecsKeys = [];
        $bodySpecsValues = [];
        $neckSpecsKeys = [];
        $neckSpecsValues = [];
        $electronicsAndStringsSpecsKeys = [];
        $electronicsAndStringsSpecsValues = [];

        foreach ($bodySpecs as $bodySpec) {
            if (preg_match('/(.*):(.*)/', $bodySpec->textContent, $matches)) {
                $bodySpecsKeys[] = $matches[1];
                $bodySpecsValues[] = $matches[2];
            }
        }
        $bodySpecs = array_combine($bodySpecsKeys, $bodySpecsValues);

        foreach ($neckSpecs as $neckSpec) {
            if (preg_match('/(.*):(.*)/', $neckSpec->textContent, $matches)) {
                $neckSpecsKeys[] = $matches[1];
                $neckSpecsValues[] = $matches[2];
            }
        }
        $neckSpecs = array_combine($neckSpecsKeys, $neckSpecsValues);

        foreach ($electronicsAndStringsSpecs as $electronicsAndStringsSpec) {
            if (preg_match('/(.*):(.*)/', $electronicsAndStringsSpec->textContent, $matches)) {
                $electronicsAndStringsSpecsKeys[] = $matches[1];
                $electronicsAndStringsSpecsValues[] = $matches[2];
            }
        }
        $electronicsAndStringsSpecs = array_combine($electronicsAndStringsSpecsKeys, $electronicsAndStringsSpecsValues);

        //____________________CRAWL-OUTPUT
        $outputContent = json_encode([
            'model' => $model,
            'description' => $description,
            'body' => $bodySpecs,
            'neck' => $neckSpecs,
            'electronicsandstrings' => $electronicsAndStringsSpecs
        ]);

        return $outputContent;
    }

    public function getIbanezData()
    {
        //$request = new HTTPCli
    }
}
