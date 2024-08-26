<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\AasMarc;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Repository\TitleRepository;
use App\Repository\PersonRepository;
use App\Repository\FirmRepository;
use App\Services\JsonLdSerializer;
use App\Services\NinesSerializer;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use ZipArchive;
use Exception;

#[AsCommand(name: 'wphp:export')]
class ExportCommand extends Command {
    public function __construct(
        protected EntityManagerInterface $em,
        protected TitleRepository $titleRepository,
        protected PersonRepository $personRepository,
        protected FirmRepository $firmRepository,
        protected ParameterBagInterface $parameterBagInterface,
        protected Filesystem $filesystem,
        protected JsonLdSerializer $jsonLdSerializer,
        protected NinesSerializer $ninesSerializer,
        protected int $pageSize = 100,
        protected ?string $exportTmpRootDir = null,
        protected ?string $zipFilePath = null,
        protected ?string $format = null,
        protected ?OutputInterface $output = null,
    ) {
        parent::__construct();
    }

    protected function configure() : void {
        $this->setDescription('Export Podcast to a supported format.');
        $this->addArgument(
            'format',
            InputArgument::REQUIRED,
            'Format to export to. One of ["nines", "18thConnect", "jsonld", "rdfxml"]'
        );
    }

    protected function dumpTitles() : void {
        $this->output->writeln('Generating title files');

        $page = 0;
        while (true) {
            $iterated = false;
            $iterable = $this->titleRepository->createQueryBuilder('t')
                ->where('t.finalcheck = 1')
                ->orWhere('t.finalattempt = 1')
                ->setMaxResults($this->pageSize)
                ->setFirstResult($page * $this->pageSize)
                ->getQuery()
                ->toIterable();

            foreach ($iterable as $title) {
                $this->output->writeln("TITLE ID: {$title->getId()}");
                if ($this->format === 'nines' && (!$title->getPubdate() || $this->ninesSerializer->isNines($title))) {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/title_{$title->getId()}.rdf", $this->ninesSerializer->getTitle($title));
                } else if ($this->format === '18thConnect' && $this->ninesSerializer->is18thConnect($title)) {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/title_{$title->getId()}.rdf", $this->ninesSerializer->getTitle($title));
                } else if ($this->format === 'jsonld') {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/title_{$title->getId()}.jsonld", $this->jsonLdSerializer->getTitle($title));
                } else if ($this->format === 'rdfxml') {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/title_{$title->getId()}.rdf", $this->jsonLdSerializer->toRDF($this->jsonLdSerializer->getTitle($title, true)));
                }
                $this->em->detach($title);
                $iterated = true;
            }

            $this->em->clear();
            if (!$iterated) {
                break;
            }
            ++$page;
        }
    }

    protected function dumpFirms() : void {
        $this->output->writeln('Generating firm files');

        $page = 0;
        while (true) {
            $iterated = false;
            $iterable = $this->firmRepository->createQueryBuilder('f')
                ->where('f.finalcheck = 1')
                ->setMaxResults($this->pageSize)
                ->setFirstResult($page * $this->pageSize)
                ->getQuery()
                ->toIterable();

            foreach ($iterable as $firm) {
                if ($this->format === 'jsonld') {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/firm_{$firm->getId()}.jsonld", $this->jsonLdSerializer->getFirm($firm));
                } else if ($this->format === 'rdfxml') {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/firm_{$firm->getId()}.rdf", $this->jsonLdSerializer->toRDF($this->jsonLdSerializer->getFirm($firm)));
                }
                $this->em->detach($firm);
                $iterated = true;
            }

            $this->em->clear();
            if (!$iterated) {
                break;
            }
            ++$page;
        }
    }

    protected function dumpPeople() : void {
        $this->output->writeln('Generating person files');

        $page = 0;
        while (true) {
            $iterated = false;
            $iterable = $this->personRepository->createQueryBuilder('p')
                ->where('p.finalcheck = 1')
                ->setMaxResults($this->pageSize)
                ->setFirstResult($page * $this->pageSize)
                ->getQuery()
                ->toIterable();

            foreach ($iterable as $person) {
                if ($this->format === 'jsonld') {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/person_{$person->getId()}.jsonld", $this->jsonLdSerializer->getPerson($person));
                } else if ($this->format === 'rdfxml') {
                    $this->filesystem->dumpFile("{$this->exportTmpRootDir}/person_{$person->getId()}.rdf", $this->jsonLdSerializer->toRDF($this->jsonLdSerializer->getPerson($person)));
                }
                $this->em->detach($person);
                $iterated = true;
            }

            $this->em->clear();
            if (!$iterated) {
                break;
            }
            ++$page;
        }
    }

    protected function zip() : void {
        $this->output->writeln('Compressing export files');
        $zip = new ZipArchive();
        if ( ! $zip->open($this->zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            throw new Exception('There was a problem creating the zip file');
        }
        $finder = new Finder();
        $finder->files()->in("{$this->exportTmpRootDir}/");
        $currentFile = 0;
        foreach ($finder as $file) {
            $currentFile++;
            $zip->addFile($file->getRealpath(), $file->getRelativePathname());
            $zip->setCompressionName('bar.jpg', ZipArchive::CM_DEFLATE, 9);
        }
        $zip->registerProgressCallback(0.05, function ($r) : void {
            // we don't know how many files there are beforehand so we approximate the increase by
            // file completion fraction multiplied by the total episodes (why we do *2 episodes steps previously)
            $percent = (int) ($r * 100);
            $this->output->writeln("Compressing export files ({$percent}%)");
        });
        if ( ! $zip->close()) {
            throw new Exception('There was a problem saving the zip file');
        }
    }

    protected function mv() : void {
        $this->output->writeln('moving zipped export file');

        $this->filesystem->mkdir($this->parameterBagInterface->get('export_root_dir'), 0o777);
        $appExportPath = $this->parameterBagInterface->get('export_root_dir') . "/{$this->format}.zip";
        $this->filesystem->rename($this->zipFilePath, $appExportPath, true);
        $this->output->writeln("Export available at: {$appExportPath}");
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $this->output = $output;
        $this->format = $input->getArgument('format');
        if (!in_array($this->format, ["nines", "18thConnect", "jsonld", "rdfxml"])) {
            $this->output->writeln('Invalid format.');
            return 0;
        }
        $this->exportTmpRootDir = sys_get_temp_dir() . "/exports/{$this->format}";
        $this->zipFilePath = "{$this->exportTmpRootDir}.zip";
        if ($this->filesystem->exists($this->exportTmpRootDir)) {
            $this->filesystem->remove($this->exportTmpRootDir);
        }
        $this->filesystem->mkdir($this->exportTmpRootDir, 0o777);
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        $this->dumpTitles();
        if (in_array($this->format, ['jsonld', 'rdfxml'])) {
            $this->dumpFirms();
            $this->dumpPeople();
        }
        $this->zip();
        $this->mv();

        $this->output->writeln("{$this->format} Export Complete");
        return 1;
    }
}
