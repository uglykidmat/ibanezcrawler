<?php

namespace App\Command;

use App\Entity\Guitar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Ugly\PDFMaker\tFPDF;

#[AsCommand(
    name: 'app:shipandzip',
    description: 'Takes a guitar serie/family (S, RG, ...) as argument, and exports a .zip file containing JSON infos and a PDF, for every model of the serie.',
    hidden: false,
)]
class ShipAndZipCommand extends Command
{
    public function __construct(
        public EntityManagerInterface $entityManager,
        public SerializerInterface $serializer,
        public tFPDF $fpdf,
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->fpdf = $fpdf;
    }

    protected function configure(): void
    {
        $this->addArgument(
            'family',
            InputArgument::REQUIRED,
            'The guitar serie/family to export as zipped PDFs.'
        );

        $this->setHelp('This command allows you to export zip files containing : .pdf and .json info sheets of each guitar known in the database (already crawled from the poopoofandom site).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $family = $input->getArgument('family');

        $family = strlen($family) > 3 ? ucfirst($family) : strtoupper($family);

        $io->note('🤐  Starting 🤐  serie ' . $family . ' ...');

        if (!count($allGuitarsFromFamily = $this->entityManager->getRepository(Guitar::class)->findByFamily($family)) > 0) {
            $io->error(['result' => 'error', 'reason' => 'No entry in the database for this family !']);
            return Command::FAILURE;
        }

        #Create folder
        if (!file_exists(__DIR__ . '/../../public/data/' . $family)) {
            mkdir(__DIR__ . '/../../public/data/' . $family, 0777, true);
        }

        // Batch create JSON and PDF files for each guitar from the family

        // Counter
        $nbProcessed = 0;
        $nbTotal = count($allGuitarsFromFamily);

        // PDF generic/header infos
        $this->fpdf->SetCreator('UglyKidMat');
        $this->fpdf->AddPage();
        $this->fpdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $this->fpdf->SetFont('DejaVu', '', 14);
        echo 'X initial : ' . $this->fpdf->GetX() . PHP_EOL;
        echo 'Y initial : ' . $this->fpdf->GetY() . PHP_EOL;
        // Ibanez Logo
        $this->fpdf->Image(__DIR__ . '/../../public/assets/ibanez-logo-small-swoosh-300.png', 10, 10);

        echo 'X after image : ' . $this->fpdf->GetX() . PHP_EOL;
        echo 'Y after image : ' . $this->fpdf->GetY() . PHP_EOL;

        foreach ($allGuitarsFromFamily as $guitar) {
            $nbProcessed++;
            $io->text($nbProcessed . '/' . $nbTotal . ' - 🎸 - Starting guitar ' . $guitar->getModel() . ' ...');

            // JSON file
            $io->text('...creating JSON file...');
            $jsonGuitar = $this->serializer->serialize(
                $guitar,
                'json',
                [
                    'json_encode_options' =>
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => true
                ],
            );

            file_put_contents(__DIR__ . '/../../public/data/' . $family . '/' . $guitar->getModel() . '.json', $jsonGuitar);

            // PDF file
            $io->text('...creating PDF file...');
            $this->fpdf->SetTitle('Ibanez ' . $guitar->getModel());

            // 
            $this->fpdf->Cell(
                0,
                40,
                'Specifications for Ibanez '
                . $guitar->getModel(),
                'TB',
                2,
                'R'
            );
            echo 'X after cell : ' . $this->fpdf->GetX() . PHP_EOL;
            echo 'Y after cell : ' . $this->fpdf->GetY() . PHP_EOL;

            $this->fpdf->Ln(5);
            $this->fpdf->SetFont('DejaVu', '', 9);

            $this->fpdf->MultiCell(0, 5, 'Description : '
                . $guitar->getDescription(), 'J');
            echo 'X after multicell description : ' . $this->fpdf->GetX() . PHP_EOL;
            echo 'Y after multicell description : ' . $this->fpdf->GetY() . PHP_EOL;

            $this->fpdf->Ln(5);

            $colours = [''];
            $this->fpdf->SetFillColor('#f72585');

            foreach ($guitar->getAllFields() as $guitarProperty => $propertyValue) {
                if (!in_array($guitarProperty, ['id', 'model', 'description'], true) && $propertyValue) {
                    $this->fpdf->Cell(70, 8, $guitarProperty);
                }
            }

            echo 'X after multicell 2 : ' . $this->fpdf->GetX() . PHP_EOL;
            echo 'Y after multicell 2 : ' . $this->fpdf->GetY() . PHP_EOL;

            $this->fpdf->Output(
                'F',
                __DIR__ . '/../../public/data/' . $family . '/' . $guitar->getModel() . '.pdf',
                true
            );

            dd('🫀🫀 fin !');

        }

        $io->success([
            '🫀  Fantastic ! 🫀  The zip file with your PDFs (' . $nbProcessed . ' entries !) is in public/data/' . $family . '/.'
        ]);

        return Command::SUCCESS;
    }
}
