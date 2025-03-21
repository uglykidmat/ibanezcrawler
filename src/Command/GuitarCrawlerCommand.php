<?php

namespace App\Command;

use App\Crawler\GuitarCrawler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:guitarcrawler',
    description: 'Crawls an Ibanez model Category.',
    hidden: false,
)]
class GuitarCrawlerCommand extends Command
{
    public function __construct(
        private GuitarCrawler $guitarCrawler,
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
        $this->addArgument(
            'funxion',
            InputArgument::REQUIRED,
            'Add to / purge from db.',
            null,
            ['crawl', 'addtodb', 'purgefromdb']
        );

        $this->setHelp('This command allows you to crawls a guitar model category or add them to database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $model = $input->getArgument('model');
        $model = strlen($model) > 3 ? ucfirst($model) : strtoupper($model);
        $funxion = $input->getArgument('funxion');
        $nbProcessed = 0;

        $io->title('⏳ Benchmarking performance');
        $start = microtime(true);

        switch ($funxion) {
            case 'crawl':
                $io->note('🕷️  Parsing 🕷️  model ' . $model . ' ...');
                $modelCategoryResult = $this->guitarCrawler->crawlGuitarCategory($model);
                file_put_contents(__DIR__ . '/../../public/data/' . $model . '-models.json', json_encode($modelCategoryResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $io->success([
                    '🕸️  Crawl results ! 🕸️  See JSON file in public/data/',
                ]);
                $end = microtime(true);
                $executionTime = $end - $start;
                $memoryUsage = memory_get_usage(true) / 1024 / 1024;
                $io->section('Results');
                $io->success("Execution Time: {$executionTime} seconds");
                $io->success("Memory Usage: " . round($memoryUsage, 2) . " MB");

                return Command::SUCCESS;
            case 'addtodb':
                $nbProcessed = $this->guitarCrawler->addGuitarsToDb($model);
                $io->success([
                    '🕸️  Done ! 🕸️  ' . $nbProcessed . ' ' . $model . ' models added to database !',
                ]);
                $end = microtime(true);
                $executionTime = $end - $start;
                $memoryUsage = memory_get_usage(true) / 1024 / 1024;
                $io->section('Results');
                $io->success("Execution Time: {$executionTime} seconds");
                $io->success("Memory Usage: " . round($memoryUsage, 2) . " MB");

                break;
            case 'purgefromdb':
                $nbProcessed = $this->guitarCrawler->purgeGuitars($model);
                $io->success([
                    '🕸️  Done ! 🕸️  ' . $nbProcessed . ' ' . $model . ' purged from database !',
                ]);

                $end = microtime(true);
                $executionTime = $end - $start;
                $memoryUsage = memory_get_usage(true) / 1024 / 1024;
                $io->section('Results');
                $io->success("Execution Time: {$executionTime} seconds");
                $io->success("Memory Usage: " . round($memoryUsage, 2) . " MB");

                break;
            default:
                $io->info('Huh ? Wrong command bud.');
                break;
        }

        return Command::SUCCESS;
    }
}
