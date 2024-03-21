<?php

namespace App\Command;

use App\Crawler\IbanezCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:crawler',
    description: 'Crawls an Ibanez wiki URL.',
    hidden: false,
)]
class CrawlerCommand extends Command
{
    public function __construct(
        private IbanezCrawler $ibanezCrawler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'url',
            InputArgument::REQUIRED,
            'The guitar URL to crawl.'
        );
        $this->setHelp('This command allows you to crawls a guitar URL.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->text('🕷️  Parsing 🕷️' . chr(10) . $input->getArgument('url') . ' ...');
        $io->success([
            '🕸️ Crawl results ! 🕸️ ',
            $this->ibanezCrawler->crawl($input->getArgument('url'))
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
