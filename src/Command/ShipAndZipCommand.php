<?php

namespace App\Command;

use App\Crawler\Utils\GuitarPropertiesConverter;
use App\Entity\Guitar;
use Doctrine\ORM\EntityManagerInterface;
use FPDF\tFPDF;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[AsCommand(
    name: 'app:shipandzip',
    description: 'Takes a guitar serie/family (S, RG, ...) as argument, and exports a .zip file containing JSON infos and a PDF, for every model of the serie.',
    hidden: false,
)]
class ShipAndZipCommand extends Command
{
    public function __construct(
        public EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
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

        $io->note('🤐  Starting 🤐  serie '.$family.' ...');

        if (!count($allGuitarsFromFamily = $this->entityManager->getRepository(Guitar::class)->findByFamily($family)) > 0) {
            $io->error(['result' => 'error', 'reason' => 'No entry in the database for this family !']);

            return Command::FAILURE;
        }

        // Create folder
        if (!file_exists(__DIR__.'/../../public/data/'.$family)) {
            mkdir(__DIR__.'/../../public/data/'.$family, 0777, true);
        }

        // Batch create JSON and PDF files for each guitar from the family

        // Serializer/Normalizer
        $propertiesConverter = new GuitarPropertiesConverter();
        $guitarNormalizer = new ObjectNormalizer(null, $propertiesConverter);
        $guitarSerializer = new Serializer([$guitarNormalizer], [new JsonEncoder()]);

        // Counter
        $nbProcessed = 0;
        $nbTotal = count($allGuitarsFromFamily);
        $section1 = $output->section();
        $section2 = $output->section();

        foreach ($allGuitarsFromFamily as $guitar) {
            ++$nbProcessed;
            $section1->overwrite('🎸 ('.$nbProcessed.'/'.$nbTotal.') Starting guitar : '.$guitar->getModel());
            $section2->overwrite('...');

            // JSON file
            $jsonGuitar = $guitarSerializer->serialize(
                $guitar,
                'json',
                [
                    'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                    'preserve_empty_objects' => false,
                ],
            );

            file_put_contents(__DIR__.'/../../public/data/'.$family.'/'.$guitar->getModel().'.json', $jsonGuitar);

            // PDF file
            // PDF generic/header infos
            $tfPDF = new tFPDF();
            $tfPDF->SetCreator('UglyKidMat');
            $tfPDF->AddPage();
            $tfPDF->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
            $tfPDF->SetFont('DejaVu', '', 14);

            // Ibanez Logo
            $tfPDF->Image(__DIR__.'/../../public/assets/ibanez-logo-small-swoosh-300.png', 10, 10);
            $tfPDF->SetTitle('Ibanez '.$guitar->getModel());
            $tfPDF->Cell(0, 40, 'Specifications for Ibanez '.$guitar->getModel(), 'TB', 2, 'R');
            $tfPDF->Ln(5);
            $tfPDF->SetFont('DejaVu', '', 9);
            $tfPDF->MultiCell(0, 5, $guitar->getDescription(), 'J');
            $tfPDF->Ln(5);

            $colours = ['0d1b2a', '1b263b', '415a77'];

            foreach ($guitar->allFields() as $guitarProperty => $propertyValue) {
                if (!in_array($guitarProperty, ['id', 'model', 'description'], true)) {
                    if (is_iterable($propertyValue)) {
                        if (0 === count($propertyValue)) {
                            $guitar->getStandardFinishes()->add('None known');
                            continue; // Skip empty iterables early
                        }
                        // String together collection items
                        $standardFinishes = '';
                        foreach ($propertyValue as $standardFinish) {
                            $standardFinishes .= $standardFinish->getName().' ('.$standardFinish->getShortName().'), ';
                        }
                        // Trim trailing comma
                        $propertyValue = rtrim($standardFinishes, ', ');
                    }

                    if ($propertyValue) {
                        $section2->overwrite('Creating table field: '.$guitarProperty);
                        $section2->overwrite('Creating table field : '.$guitarProperty);
                        $tfPDF->SetFillColor($colours[array_rand($colours, 1)]);
                        $tfPDF->SetTextColor(255);
                        $tfPDF->Cell(40, 8, $guitarProperty, 1, 0, 'L', true);
                        $tfPDF->SetTextColor(0);

                        // Handle string property values
                        if (is_string($propertyValue)) {
                            if (strlen($propertyValue) > 96) {
                                $tfPDF->MultiCell(140, 8, $propertyValue, 1, 'L');
                            } else {
                                $tfPDF->Cell(140, 8, $propertyValue, 1, 1, 'L', false);
                            }
                        }
                    }
                }
            }

            $tfPDF->Output(
                'F',
                __DIR__.'/../../public/data/'.$family.'/'.$guitar->getModel().'.pdf',
                true
            );
        }

        $section1->clear();
        $section2->clear();

        // Initiate ZIP archive
        $guitarZip = new \ZipArchive();
        $guitarZip->open(__DIR__.'/../../public/data/'.$family.'/'.$family.'_models.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $guitarZip->addPattern('/(.+)\.pdf/', __DIR__.'/../../public/data/'.$family.'/', ['remove_all_path' => true]);
        $guitarZip->close();

        $io->success([
            '🫀  Fantastic ! 🫀  The zip file with your PDFs ('.$nbProcessed.' entries !) is in public/data/'.$family.'/.',
        ]);

        return Command::SUCCESS;
    }
}
