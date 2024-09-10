# Ibanez wiki scraper

In a perfect world, nobody uses Wikia.
https://ibanez.fandom.com/ made the rookie mistake of doing this instead of wiki.gg or something less cringe.

## Tech
Symfony 7

## Commands

### Guitars
#### Crawl
`php bin/console app:guitarcrawler {serie/family name} {crawl|addtodb|purgefromdb}`

Commands :
* `crawl` will parse the guitars category and create the JSON data file `public/data/{serie/family name}-models.json` 
* `addtodb` will parse the above file and add every entry as Guitar entities in the database.
* `purgedb` will remove every Guitar entities from the database.

`string` Model name  = "RG", "S", "Prestige"...
Will crawl a page of category type (https://ibanez.fandom.com/wiki/Category:S_models) and create a JSON file of the guitar models found, under `public/data/`.

#### PDF/JSON/ZIP
`php bin/console app:shipandzip {serie/family}`

This command will create a folder under /public/data and fill it with two types of files : PDF and JSON for every guitar model of the serie, and a ZIP file contaning all the above PDF ones.

⚠️ It is required to use the above commands
guitarcrawler->crawl then ->addtodb before
using the shipandzip command, as it needs entries in the database. 

### Necks
`php bin/console app:neckcrawler {crawl|addtodb|purgefromdb}`

Commands : 
* `crawl` will parse the necks page and create the JSON data file `public/data/necks.json` 
* `addtodb` will parse the above file and add every entry as Neck entities in the database.
* `purgedb` will remove every Neck entity from the database.

## Crawler links
If someone ever uses a browser

* Crawl one guitar (example/test) : `/crawler/guitar`
* Crawl guitars by model (S, RG, Prestige, etc) : `/crawler/guitars/{serie}`
* Crawl guitar necks : `/crawler/guitarnecks`

## API endpoints
* Get a single guitar model : `/guitars/model/{model}` ("S450", "FGM100", ...)
* Get all guitars by family : `/guitars/family/{family}` ("S", "Prestige", ...)
