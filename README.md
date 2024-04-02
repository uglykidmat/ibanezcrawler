# Ibanez wiki scraper

In a perfect world, nobody uses Wikia.
https://ibanez.fandom.com/ made the rookie mistake of doing this instead of wiki.gg or something less cringe.

## Tech
Symfony 7 / PostgreSQL

## Commands

### Guitars
`php bin/console app:crawler {model name}`

`string` Model name  = "RG", "S", ...
This command will crawl a page of category type (https://ibanez.fandom.com/wiki/Category:S_models) and create a JSON file of the guitar models found, under `public/data/`.

### Necks
`php bin/console app:neckcrawler {crawl|addtodb|purgedb}`

* `crawl` will parse the necks page and create the JSON data file `public/data/necks.json` 
* `addtodb` will parse the above file and add every entry as Neck entities in the database.
* `purgedb` will remove every Neck entity from the database.

## Links

* Crawl one guitar (example/test) : /crawler/guitar
* Crawl guitars by model (S, RG, etc) : /crawler/guitars/{serie}
* Crawl guitar necks : /crawler/guitarnecks