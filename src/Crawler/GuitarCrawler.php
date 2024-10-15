<?php

namespace App\Crawler;

use App\Crawler\Utils\FinishParser;
use App\Entity\Finish;
use App\Entity\Guitar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GuitarCrawler
{
    public function __construct(
        public HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
    ) {
        $this->client = $client->withOptions([]);
        $this->entityManager = $entityManager;
    }

    public function crawlGuitarCategory(string $serie = null, string $nextPage = null, array $allGuitarsOfPage = null): array
    {
        if (!$nextPage) {
            $serie = str_replace(' ', '_', $serie);
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
        $outputCount = 0;
        foreach ($modelsURLs as $modelURL) {
            $allGuitarsOfPage[] = $this->crawlOneGuitar($modelURL);
            //____________________CRAWL-LOG!
            $outputCount++;
            echo ' ✅ [' . $outputCount . '/' . count($modelsURLs) . '] Finito el traitemento de los modelos -> ', $modelURL, ' ! Ayyyy caramba !', PHP_EOL;
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
        $details = $this->parseAndFuseData($crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[1]//tr'));
        $bodySpecs = $this->parseAndFuseData($crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[1]/table/tbody//td'));
        $neckSpecs = $this->parseAndFuseData($crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[2]/table/tbody//td'));
        $electronicsAndStringsSpecs = $this->parseAndFuseData($crawler->filterXPath('//div[@class="purplebox"]/table/tbody/tr[2]/td[3]/table/tbody//td'));
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

    private function parseAndFuseData(Crawler $crawledData): array
    {
        $dataKeys = [];
        $dataValues = [];
        foreach ($crawledData as $data) {
            if (preg_match('/(\w+\s?\/?\w+\s?\(?\w+\)?\s?\w*):(.*)/', $data->textContent, $matches)) {
                $dataKeys[] = $matches[1];
                $dataValues[] = trim(str_replace('\n', ' ', $matches[2]));
                if ($dataKeys[0] == 'Model name') {
                    if (
                        preg_match('/(\(.*\))/', $dataValues[0], $matches)
                    ) {
                        $dataValues[0] = trim(preg_replace('/(\(.*\))/', '', $dataValues[0]));
                        $extraParenthesisInfo = substr($matches[0], 1, strlen($matches[0]) - 2);
                        $dataKeys[] = 'ExtraParenthesisInfo';
                        $dataValues[] = $extraParenthesisInfo;
                    }
                }
            } else if (preg_match('/(\w+\(\w+\)):(.*)/', $data->textContent, $matches)) {
                $dataKeys[] = $matches[1];
                $dataValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+\s\w+):(.*)/', $data->textContent, $matches)) {
                $dataKeys[] = $matches[1];
                $dataValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+):(.*)/', $data->textContent, $matches)) {
                $dataKeys[] = $matches[1];
                $dataValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        return array_combine($dataKeys, $dataValues);
    }

    public function addGuitarsToDb(string $model): int
    {
        //Check Finishes
        $finishparser = new FinishParser(
            $this->client,
            $this->entityManager
        );
        $finishparser->checkFinishes();

        //Start DB insertions
        $count = 0;
        $guitars = json_decode(file_get_contents(__DIR__ . '/../../public/data/' . $model . '-models.json'), true);
        foreach ($guitars as $guitar) {
            $guitarEntity = new Guitar();
            $queryStringToEval = '$guitarEntity';
            foreach ($guitar as $key => $info) {
                if (is_iterable($guitar[$key])) {
                    foreach ($guitar[$key] as $key2 => $info2) {
                        if ($key2 == 'Finish(es)') {
                            $finishesFound =
                                array_map(
                                    fn($finish) => trim($finish),
                                    explode('/', $info2)
                                );
                            $parsedFinishesFound = [];
                            foreach ($finishesFound as $finishFound) {
                                // preg_match('/(^[a-zA-Z\s\(]+)(\([A-Z]+\))\s?(.*)?/',
                                // $finishFound, $finishMatches);
                                if (preg_match('/(^[a-zA-Z\s]+)\s(\([A-Z]+\))\s?([0-9\W]+)?/', $finishFound, $finishMatches)) {
                                    $parsedFinishesFound[] = [
                                        'name' => $finishMatches[1],
                                        'shortname' => $finishMatches[2]
                                    ];

                                    if ($finishFoundInDB = $this->entityManager->getRepository(Finish::class)->findOneByName($finishMatches[1])) {
                                        dd($finishFoundInDB);
                                    }
                                } else {
                                    $queryStringToEval .= '->setFinishes($guitar["' .
                                        $key .
                                        '"]["' .
                                        $key2 .
                                        '"])';
                                }
                            }
                            dd($queryStringToEval);
                            continue;
                        }
                        if ($key2 == 'Back/sides') {
                            $queryStringToEval .=
                                '->setBackorsides($guitar["' .
                                $key .
                                '"]["' .
                                $key2 .
                                '"])';
                        } else
                            $queryStringToEval .=
                                '->set' .
                                ucfirst(trim(str_replace([' ', '(', ')'], '', (string) $key2))) .
                                '($guitar["' .
                                $key .
                                '"]["' .
                                $key2 .
                                '"])';
                    }
                } else {
                    $queryStringToEval .=
                        '->set' .
                        ucfirst(trim(str_replace(' ', '', (string) $key))) .
                        '($guitar["' .
                        $key .
                        '"])';
                }
            }
            $queryStringToEval .= ';';

            //___________WOAH DANGEROUS
            //dd($queryStringToEval);
            eval ($queryStringToEval);
            $guitarEntity->setFamily($model);
            $this->entityManager->persist($guitarEntity);
            $count++;
            echo '🤡 Adding ' . $guitar['model'] . PHP_EOL;
        }
        $this->entityManager->flush();

        return $count;
    }

    public function purgeGuitars(string $family): int
    {
        $count = 0;
        $neckRepository = $this->entityManager->getRepository(Guitar::class);
        foreach ($neckRepository->findByFamily($family) as $guitar) {
            $this->entityManager->remove($guitar);
            $count++;
        }
        $this->entityManager->flush();

        return $count;
    }
}
