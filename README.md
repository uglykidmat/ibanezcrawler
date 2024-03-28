# Ibanez wiki scraper

In a perfect world, nobody uses Wikia.
https://ibanez.fandom.com/ made the rookie mistake of doing this instead of wiki.gg or something less cringe.

## Tech
Symfony 7

## Commands

`php bin/console app:crawler {model name}`

`string` Model name  = "RG", "S", ...
This command will crawl a page of category type (https://ibanez.fandom.com/wiki/Category:S_models) and create a JSON file of the guitar models found, under `public/data/`.

## Links

* Crawl one guitar (example/test) : /crawler/guitar
* Crawl guitars by model (S, RG, etc) : /crawler/guitars/{serie}
* Crawl guitar necks : /crawler/guitarnecks