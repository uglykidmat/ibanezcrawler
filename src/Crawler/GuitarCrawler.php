<?php

namespace App\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GuitarCrawler
{
    public function __construct(
        public HttpClientInterface $client,
    ) {
        $this->client = $client->withOptions([]);
    }

    public function crawlGuitarCategory(string $serie = null, string $nextPage = null, array $allGuitarsOfPage = null): array
    {
        if (!$nextPage) {
            $url = 'https://ibanez.fandom.com/wiki/Category:' . $serie . '_models';
        } else {
            $url = $nextPage;
        }

        $response = $this->client->request('GET', $url)->getContent();

        //____________________CRAWLER
        $crawler = new Crawler($response);

        //____________________CRAWL-LINKS
        $categoryCrawlResult = $crawler->filterXPath('//div[@class="category-page__members"]//li/a/@href');

        //____________________BUILD-MODELS_LIST_URLS
        $modelsURLs = [];
        foreach ($categoryCrawlResult as $key => $modelSubpageURL) {
            $modelsURLs[] = 'https://ibanez.fandom.com' . $modelSubpageURL->textContent;
        }

        //____________________CRAWL-ONE-BY-ONE

        foreach ($modelsURLs as $modelURL) {
            $allGuitarsOfPage[] = $this->crawlOneGuitar($modelURL);
        }

        //____________________Recursive crawl on next pages

        if ($nextPageURL = $crawler->filterXPath('//div[@class="category-page__pagination"]//a[contains(@class,"category-page__pagination-next")]/@href')->getNode(0)) {
            return $this->crawlGuitarCategory(null, $nextPageURL->textContent, $allGuitarsOfPage);
        }

        return $allGuitarsOfPage;
    }

    public function crawlOneGuitar(string $url): array
    {
        //____________________CLIENT
        $response = $this->client->request('GET', $url)->getContent();

        //____________________CRAWLER
        $crawler = new Crawler($response);

        //____________________CRAWL-TITLE
        $model = $crawler->filterXPath("//span[@class='mw-page-title-main']")->text();

        //____________________CRAWL-DESCRIPTION
        $description = '';
        $descriptionParagraphs = $crawler->filterXPath('descendant-or-self::div[@class="mw-parser-output"]//p');

        foreach ($descriptionParagraphs as $paragraph) {
            $description .= trim(preg_replace("/\r\n|\r|\n/", ' ', $paragraph->textContent));
        }

        //____________________CRAWL-DETAILS
        $details = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[1]');
        $bodySpecs = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[1]/table/tbody//td');
        $neckSpecs = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[2]/table/tbody//td');
        $electronicsAndStringsSpecs = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[3]/table/tbody//td');

        $detailsKeys = [];
        $detailsValues = [];
        $bodySpecsKeys = [];
        $bodySpecsValues = [];
        $neckSpecsKeys = [];
        $neckSpecsValues = [];
        $electronicsAndStringsSpecsKeys = [];
        $electronicsAndStringsSpecsValues = [];

        foreach ($details as $detail) {
            if (preg_match('/([a-zA-Z]*):(.*)/', $detail->textContent, $matches)) {
                $detailsKeys[] = $matches[1];
                $detailsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $details = array_combine($detailsKeys, $detailsValues);

        foreach ($bodySpecs as $bodySpec) {
            if (preg_match('/([a-zA-Z]*):(.*)/', $bodySpec->textContent, $matches)) {
                $bodySpecsKeys[] = $matches[1];
                $bodySpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $bodySpecs = array_combine($bodySpecsKeys, $bodySpecsValues);

        foreach ($neckSpecs as $neckSpec) {
            if (preg_match('/(.*):(.*)/', $neckSpec->textContent, $matches)) {
                $neckSpecsKeys[] = $matches[1];
                $neckSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $neckSpecs = array_combine($neckSpecsKeys, $neckSpecsValues);

        foreach ($electronicsAndStringsSpecs as $electronicsAndStringsSpec) {
            if (preg_match('/(.*):(.*)/', $electronicsAndStringsSpec->textContent, $matches)) {
                $electronicsAndStringsSpecsKeys[] = $matches[1];
                $electronicsAndStringsSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $electronicsAndStringsSpecs = array_combine($electronicsAndStringsSpecsKeys, $electronicsAndStringsSpecsValues);

        //____________________CRAWL-OUTPUT
        return [
            'model' => $model,
            'description' => $description,
            'details' => $details,
            'body' => $bodySpecs,
            'neck' => $neckSpecs,
            'electronicsandstrings' => $electronicsAndStringsSpecs
        ];
    }
}
