<?php

namespace App\Command;

use App\Crawler\GuitarCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:crawler',
    description: 'Crawls an Ibanez model Category.',
    hidden: false,
)]
class CrawlerCommand extends Command
{
    public function __construct(
        private GuitarCrawler $ibanezCrawler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'model',
            InputArgument::REQUIRED,
            'The guitar model to crawl.'
        );
        $this->setHelp('This command allows you to crawls a guitar model category.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $model = $input->getArgument('model');
        $model = strlen($model) > 3 ? ucfirst($model) : strtoupper($model);

        $io->note('🕷️  Parsing 🕷️  model ' . $model . ' ...');

        $modelCategoryResult = $this->ibanezCrawler->crawlGuitarCategory($model);

        file_put_contents(__DIR__ . '/../../public/data/' . $model . '-models.json', json_encode($modelCategoryResult, JSON_PRETTY_PRINT));

        $io->success([
            '🕸️  Crawl results ! 🕸️  See JSON file in public/data/'
        ]);

        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
