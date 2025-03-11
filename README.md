# Ibanez wiki scraper

DISCLAIMER : this is a small project I built for fun and learning. It has no
particular use case. I just like requesting, parsing and PHP'ing in general. In
my wildest dreams, this backend would be used by a frontend app using Three.js or
something, for displaying guitar models in 3D. I am NOT there yet 🤧.

Anyway : in a perfect world, nobody uses Wikia.
https://ibanez.fandom.com/ made the rookie mistake of doing this instead of wiki.gg or something less cringe.

## Tech
Symfony 7, postgreSQL 16

## Installation
### DDEV
1. `git clone https://github.com/uglykidmat/ibanezcrawler.git /path/to/your/garbage-projects/folder`
2. `cd /path/to/your/garbage-projects/folder`
2. `ddev config`
3. `ddev start`
4. `ddev composer install`
   
### Docker
I used [FrankenPHP's "Symfony-docker"
image](https://github.com/dunglas/symfony-docker/blob/main/docs/existing-project.md).
Follow their "installing on an existing project" doc after having cloned this
repo, then "docker compose up --build -d" will spin up the app and its database.

### Other
Clone te project, update the .env file with
`DATABASE_URL="postgres://user:pass@postgres:5432/ibanez?charset=utf8&serverVersion=16"`,
with the correct user/pass/dbaddress values for your local postgreSQL instance.

## Commands

### TL;DR
Example for S serie
* `php bin/console app:guitarcrawler s crawl`
* `php bin/console app:guitarcrawler s addtodb`
* `php bin/console app:shipandzip s`
* `php bin/console app:guitarcrawler s purgefromdb`

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

## database
I used PostgreSQL 16 but it should work fine with MySQL. Obviously, don't forget to set your .env with
* DATABASE_URL="postgresql://user:pass@address:port/ibanez?serverVersion=version&charset=utf8"