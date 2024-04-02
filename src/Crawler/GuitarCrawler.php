<?php

namespace App\Crawler;

use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GuitarCrawler
{

    public function __construct(
        public HttpClientInterface $client,
        private LoggerInterface $logger

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

        echo ' 🎸 Esta partita por las URLas -> ', $url, ' ! Madre mia !', PHP_EOL;

        $response = $this->client->request('GET', $url)->getContent();

        //____________________CRAWLER
        $crawler = new Crawler($response);

        //____________________CRAWL-LINKS
        $categoryCrawlResult = $crawler->filterXPath('//div[@class="category-page__members"]//li/a[@class="category-page__member-link"][not(contains(@href,"Category"))]/@href');

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

        echo PHP_EOL;

        return $allGuitarsOfPage;
    }

    public function crawlOneGuitar(string $url): array
    {
        //____________________CLIENT
        $response = $this->client->request('GET', $url)->getContent();

        //____________________CRAWLER
        $crawler = new Crawler($response);

        //____________________CRAWL-TITLE
        $model = trim($crawler->filterXPath("//div[@class='page-header__title-wrapper']")->getNode(0)->nodeValue);

        //____________________CRAWL-DESCRIPTION
        $description = '';
        $descriptionParagraphs = $crawler->filterXPath('descendant-or-self::div[@class="mw-parser-output"]//p');

        foreach ($descriptionParagraphs as $paragraph) {
            $description .= trim(preg_replace("/\r\n|\r|\n/", ' ', $paragraph->textContent));
        }

        //____________________CRAWL-DETAILS
        $details = $crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[1]//tr');
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
            if (preg_match('/(\w+\s\w+):(.*)/', $detail->textContent, $matches)) {
                $detailsKeys[] = $matches[1];
                $detailsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $details = array_combine($detailsKeys, $detailsValues);

        foreach ($bodySpecs as $bodySpec) {
            if (preg_match('/(\w+\s\w+):(.*)/', $bodySpec->textContent, $matches)) {
                $bodySpecsKeys[] = $matches[1];
                $bodySpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $bodySpecs = array_combine($bodySpecsKeys, $bodySpecsValues);

        foreach ($neckSpecs as $neckSpec) {
            if (preg_match('/(\w+\s\w+):(.*)/', $neckSpec->textContent, $matches)) {
                $neckSpecsKeys[] = $matches[1];
                $neckSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $neckSpecs = array_combine($neckSpecsKeys, $neckSpecsValues);

        foreach ($electronicsAndStringsSpecs as $electronicsAndStringsSpec) {
            if (preg_match('/(\w+\s\w+):(.*)/', $electronicsAndStringsSpec->textContent, $matches)) {
                $electronicsAndStringsSpecsKeys[] = $matches[1];
                $electronicsAndStringsSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $electronicsAndStringsSpecs = array_combine($electronicsAndStringsSpecsKeys, $electronicsAndStringsSpecsValues);

        //____________________CRAWL-LOG!
        //$logger->emergency('HOULALALA');
        // $this->logger->info('About to find a happy message!');
        // $this->logger->info('About to find a happy message!');
        // $this->logger->notice('notice ?');
        // $this->logger->warning('warning ?');
        // $this->logger->error('error ?');
        // $this->logger->debug('debug ?');
        // $this->logger->info('info ?');
        // $this->logger->emergency('emergency ?');
        //$this->logger->log('heu', ...$details);
        echo ' ✅ Finito el traitemento de los modelos -> ', $model, ' ! Ayyyy caramba !', PHP_EOL;
        //dd($model);

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
