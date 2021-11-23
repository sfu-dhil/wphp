<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Repository\EstcMarcRepository;
use App\Services\EstcMarcImporter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpMarc\Field;
use PhpMarc\File;
use PhpMarc\Record;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertEstcCommand extends Command {
    //100 - Personal Name
    //a. Personal name
    //c. titles
    //q. fuller form of name
    //260 - Publication
    //b. Name of publisher
    //f. Manufacturer
    //264 - Production
    //b. Name of producer
    //700 - Added entry - Personal Name
    //a. Personal name
    //c. titles
    //q. fuller form of name

    public const NAME_FIELDS = [
        '100' => ['a', 'q'],
        '700' => ['a', 'q'],
    ];

    public const TITLE_FIELDS = [
        '100' => ['c'],
        '700' => ['c'],
    ];

    public const NAME_PUNCT = [
        '/(\w\w).$/u' => '$1',
        '/,$/u' => '',
    ];

    private bool $save = false;

    private EntityManagerInterface $em;

    private int $n = 0;

    /**
     * @var string[]
     */
    private array $names;

    private EstcMarcImporter $importer;

    /**
     * @var array|array[]|false[]|null[]|string[]|\string[][]
     */
    private array $titles;

    private EstcMarcRepository $estcMarcRepository;

    protected static $defaultName = 'wphp:convert:estc';

    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure() : void {
        $this->setDescription(self::$defaultDescription);
        $this->addArgument('path', InputArgument::IS_ARRAY, 'One or more files to import');
        $this->addOption('save', null, InputOption::VALUE_NONE, 'Save converted records');
    }

    protected function dot(bool $finished = false) : void {
        $this->n++;
        if ($this->save) {
            $this->em->flush();
            $this->em->clear();
        }
        echo "\r{$this->n}";
        if ($finished) {
            echo "\nfinished\n";
        }
    }

    /**
     * Wrapper around $this->em->persist that does nothing unless self::SAVE is
     * true.
     */
    protected function save(object $object) : void {
        if ($this->save) {
            $this->em->persist($object);
        }
    }

    /**
     * Read the list of women's names and titles.
     */
    protected function getNames() : void {
        $data = file_get_contents('data/women.txt');
        $data = preg_split("/\n/u", $data);
        $data = array_map(fn ($s) => preg_replace('/^\s+|\s+$/u', '', $s), $data);
        $data = array_filter($data);
        $data = array_map(fn ($s) => mb_convert_case($s, MB_CASE_LOWER), $data);
        $this->names = $data;

        $data = file_get_contents('data/titles.txt');
        $data = preg_split("/\n/u", $data);
        $data = array_map(fn ($s) => preg_replace('/^\s+|\s+$/u', '', $s), $data);
        $data = array_filter($data);
        $data = array_map(fn ($s) => mb_convert_case($s, MB_CASE_LOWER), $data);
        $this->titles = $data;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
//        set_error_handler([$this, 'errorHandler']);
        $this->save = $input->getOption('save');
        $this->getNames();

        foreach ($input->getArgument('path') as $path) {
            echo "\n{$path}\n";
            $file = new File();
            $file->file($path);
            $record = $file->next();
            while ($record) {
                if ($this->checkTitle($record) || $this->checkName($record)) {
//                    $estcMarc = $this->estcMarcRepository->findOneBy([
//                        'field' => '001',
//                        'fieldData' => $record->field('001')->data,
//                    ]);
//                    $title = $this->importer->import($estcMarc->getTitleId());
//                    foreach($title->getTitleSources() as $ts) {
//                        $this->em->persist($ts);
//                    }
//                    $this->save($title);
                }
//                $this->dot(false);

                try {
                    $record = $file->next();
                } catch (Exception $e) {
                    $output->writeln("\n" . $e->getMessage());
                }
            }
        }
        $this->dot(true);

        return 0;
    }

    protected function checkTitle(Record $record) : bool {
        foreach (self::TITLE_FIELDS as $id => $subs) {
            $fields = $record->fields[$id] ?? [];
            foreach ($fields as $field) {
                foreach ($subs as $s) {
                    if ( ! ($data = $field->subfields[$s] ?? null)) {
                        continue;
                    }
                    foreach ($this->titles as $title) {
                        $m = [];
                        if (preg_match("/\\b({$title})\\b/ui", $data, $m)) {
                            echo implode("|", [
                                'title', $title, $data, $id . $s, $record->field('001')->data(),
                                    $record->subfield('245', 'a'), 'http://estc.bl.uk/' . $record->field('001')->data()
                            ]) . "\n";
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    protected function checkName(Record $record) : bool {
        foreach (self::NAME_FIELDS as $id => $subs) {
            /** @var Field[] $fields */
            $fields = $record->fields[$id] ?? [];
            foreach ($fields as $field) {
                foreach ($subs as $s) {
                    if (( ! $data = $field->subfields[$s] ?? null)) {
                        continue;
                    }
                    $given = preg_replace('/^[^,]*,\\s*/u', '', $data);
                    foreach ($this->names as $name) {
                        $m = [];
                        if (preg_match("/\\b({$name})\\b/ui", $given, $m)) {
                            echo implode("|", [
                                    'name', $name, $data, $id . $s, $record->field('001')->data(),
                                    $record->subfield('245', 'a'), 'http://estc.bl.uk/' . $record->field('001')->data()
                                ]) . "\n";
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function errorHandler(int $errno, string $errstr, string $errfile, int $errline, array $errcontext) : void {
        throw new Exception($errstr, $errno);
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setEstcMarcImporter(EstcMarcImporter $importer) : void {
        $this->importer = $importer;
    }

    /**
     * @required
     */
    public function setEstcMarcRepository(EstcMarcRepository $estcMarcRepository) : void {
        $this->estcMarcRepository = $estcMarcRepository;
    }
}
