<?php

namespace App\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;

class IbanezCrawler
{

    public function crawl(): JsonResponse
    {
        $url = 'https://www.6annonce.net/escort/alma-177543';

        $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <p class="message">Hello World!</p>
        <p>Hello Crawler!</p>
    </body>
</html>
HTML;

        $crawler = new Crawler($html);



        dd($crawler->filter('body > p')->first());

        $allnodes = [];
        foreach ($crawler as $domElement) {
            //$allnodes[] = $domElement->nodeName;
            var_dump($domElement->nodeName);
        }
        //dd($allnodes);

        $output = new JsonResponse();
        $output->setContent('ok');
        return $output;
    }
}
