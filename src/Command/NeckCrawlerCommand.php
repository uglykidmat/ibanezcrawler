<?php

namespace App\Command;

use App\Crawler\NeckCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:neckcrawler',
    description: 'Crawls Ibanez Necks page.',
    hidden: false,
)]
class NeckCrawlerCommand extends Command
{
    public function __construct(
        private NeckCrawler $neckCrawler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'funxion',
            InputArgument::REQUIRED,
            'The funxion to execute.',
            null,
            ['crawl', 'addtodb', 'purgedb']
        );
        $this->setHelp('funxion to execute by the neck crawler.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $funxion = $input->getArgument('funxion');
        $io->text('🕷️ Executing 🕷️ funxion ' . $funxion . ' ...');

        $nbProcessed = 0;
        switch ($funxion) {
            case 'crawl':
                $this->neckCrawler->crawlGuitarNecks();
                break;
            case 'addtodb':
                $nbProcessed = $this->neckCrawler->addNecksToDb();
                break;
            case 'purgedb':
                $nbProcessed = $this->neckCrawler->purgeGuitarNecks();
                break;
            default:
                $io->info('Huh ? Wrong command bud.');
                break;
        }

        $io->success([
            '🕸️  ' . $funxion . ' done with ' . $nbProcessed . ' entries ! 🕸️'
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
