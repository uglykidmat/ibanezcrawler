<?php

namespace App\Crawler;

use App\Entity\Guitar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GuitarCrawler
{

    public function __construct(
        public HttpClientInterface $client,
        private EntityManagerInterface $entityManager
    ) {
        $this->client = $client->withOptions([]);
        $this->entityManager = $entityManager;
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
            if (preg_match('/(\w+\(\w+\)):(.*)/', $detail->textContent, $matches)) {
                $detailsKeys[] = $matches[1];
                $detailsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+):(.*)/', $detail->textContent, $matches)) {
                $detailsKeys[] = $matches[1];
                $detailsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+\s\w+):(.*)/', $detail->textContent, $matches)) {
                $detailsKeys[] = $matches[1];
                $detailsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $details = array_combine($detailsKeys, $detailsValues);

        foreach ($bodySpecs as $bodySpec) {
            if (preg_match('/(\w+\(\w+\)):(.*)/', $bodySpec->textContent, $matches)) {
                $bodySpecsKeys[] = $matches[1];
                $bodySpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+):(.*)/', $bodySpec->textContent, $matches)) {
                $bodySpecsKeys[] = $matches[1];
                $bodySpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+\s\w+):(.*)/', $bodySpec->textContent, $matches)) {
                $bodySpecsKeys[] = $matches[1];
                $bodySpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $bodySpecs = array_combine($bodySpecsKeys, $bodySpecsValues);

        foreach ($neckSpecs as $neckSpec) {
            if (preg_match('/(\w+\(\w+\)):(.*)/', $neckSpec->textContent, $matches)) {
                $neckSpecsKeys[] = $matches[1];
                $neckSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+):(.*)/', $neckSpec->textContent, $matches)) {
                $neckSpecsKeys[] = $matches[1];
                $neckSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+\s\w+):(.*)/', $neckSpec->textContent, $matches)) {
                $neckSpecsKeys[] = $matches[1];
                $neckSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $neckSpecs = array_combine($neckSpecsKeys, $neckSpecsValues);

        foreach ($electronicsAndStringsSpecs as $electronicsAndStringsSpec) {
            if (preg_match('/(\w+\(\w+\)):(.*)/', $electronicsAndStringsSpec->textContent, $matches)) {
                $electronicsAndStringsSpecsKeys[] = $matches[1];
                $electronicsAndStringsSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+):(.*)/', $electronicsAndStringsSpec->textContent, $matches)) {
                $electronicsAndStringsSpecsKeys[] = $matches[1];
                $electronicsAndStringsSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            } else if (preg_match('/(\w+\s\w+):(.*)/', $electronicsAndStringsSpec->textContent, $matches)) {
                $electronicsAndStringsSpecsKeys[] = $matches[1];
                $electronicsAndStringsSpecsValues[] = trim(str_replace('\n', ' ', $matches[2]));
            }
        }
        $electronicsAndStringsSpecs = array_combine($electronicsAndStringsSpecsKeys, $electronicsAndStringsSpecsValues);

        //____________________CRAWL-LOG!
        echo ' ✅ Finito el traitemento de los modelos -> ', $model, ' ! Ayyyy caramba !', PHP_EOL;

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


    public function addGuitarsToDb(string $model): int
    {
        $count = 0;
        $guitars = json_decode(file_get_contents(__DIR__ . '/../../public/data/' . $model . '-models.json'), true);

        $guitarEntity = new Guitar();

        foreach ($guitars as $guitar) {
            $queryStringToEval = '$guitarEntity';
            foreach ($guitar as $key => $info) {
                if (is_iterable($guitar[$key])) {
                    foreach ($guitar[$key] as $key2 => $info2) {
                        $queryStringToEval .=
                            '->set' .
                            ucfirst(trim(str_replace(' ', '', (string)$key2))) .
                            '($guitar["' .
                            $key .
                            '"]["' .
                            $key2 .
                            '"])';
                        if ($key == '7tremolo2010' || $key2 == '7tremolo2010') {
                            dd($guitar);
                        }
                    }
                } else {
                    $queryStringToEval .=
                        '->set' .
                        ucfirst(trim(str_replace(' ', '', (string)$key))) .
                        '($guitar["' .
                        $key .
                        '"])';
                }
                if ($key == '7tremolo2010') {
                    dd($guitar);
                }
            }
            $queryStringToEval .= ';';

            //___________WOAH DANGEROUS
            //dd($queryStringToEval);

            eval($queryStringToEval);


            // $guitarEntity
            //     ->setModel($guitar['model'])
            //     ->setDescription($guitar['description'])
            //     ->setMadein($guitar['details']['Made in'])
            //     ->setBodytype($guitar['body']['Body type'])
            //     ->setBodymaterial($guitar['body']['Body material'])
            //     ->setNeckjoint($guitar['body']['Neck joint'])
            //     ->setHardwarecolor($guitar['body']['Hardware color'])
            //     ->setNecktype($guitar['neck']['Neck type'])
            //     ->setNeckmaterial($guitar['neck']['Neck material'])
            //     ->setFingerboardmaterial($guitar['neck']['Fingerboard material'])
            //     ->setPickupconfiguration($guitar['electronicsandstrings']['Pickup configuration'])
            //     ->setBridgepickup($guitar['electronicsandstrings']['Bridge pickup']);

            // if (isset($guitar['details']['Model name'])) {
            //     $guitarEntity->setModelname($guitar['details']['Model name']);
            // }
            // if (isset($guitar['electronicsandstrings']['Neck pickup'])) {
            //     $guitarEntity->setNeckpickup($guitar['electronicsandstrings']['Neck pickup']);
            // }
            // if (isset($guitar['electronicsandstrings']['Neck pickup'])) {
            //     $guitarEntity->setNeckpickup($guitar['electronicsandstrings']['Neck pickup']);
            // }
            // if (isset($guitar['details']['Sold in'])) {
            //     $guitarEntity->setSoldin($guitar['details']['Sold in']);
            // }
            // if (isset($guitar['neck']['Fingerboard inlays'])) {
            //     $guitarEntity->setFingerboardinlays($guitar['neck']['Fingerboard inlays']);
            // }
            // if (isset($guitar['neck']['Machine heads'])) {
            //     $guitarEntity->setMachineheads($guitar['neck']['Machine heads']);
            // }
            // if (isset($guitar['electronicsandstrings']['Output jack'])) {
            //     $guitarEntity->setOutputjack($guitar['electronicsandstrings']['Output jack']);
            // }
            // if (isset($guitar['electronicsandstrings']['Middle pickup'])) {
            //     $guitarEntity->setMiddlepickup($guitar['electronicsandstrings']['Middle pickup']);
            // }
            // if (isset($guitar['electronicsandstrings']['Factory tuning'])) {
            //     $guitarEntity->setFactorytuning($guitar['electronicsandstrings']['Factory tuning']);
            // }
            // if (isset($guitar['electronicsandstrings']['Factory tuning'])) {
            //     $guitarEntity->setFactorytuning($guitar['electronicsandstrings']['Factory tuning']);
            // }
            //dd($guitarEntity);
            $this->entityManager->persist($guitarEntity);
            $count++;
        }
        $this->entityManager->flush();

        return $count;
    }

    public function purgeGuitars(string $model): int
    {
        $count = 0;
        $neckRepository = $this->entityManager->getRepository(Guitar::class);

        foreach ($neckRepository->findByModel($model) as $guitar) {
            $this->entityManager->remove($guitar);
            $count++;
        }
        $this->entityManager->flush();

        return $count;
    }
}
