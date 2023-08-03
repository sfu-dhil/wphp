<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\EstcMarc;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpMarc\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'wphp:import:estc')]
class ImportEstcCommand extends Command {
    public function __construct(
        private EntityManagerInterface $em,
        private int $n = 0,
        private bool $save = false,
    ) {
        parent::__construct(null);
    }

    protected function configure() : void {
        $this->setDescription('Import ESTC MARC data');
        $this->addArgument('path', InputArgument::IS_ARRAY, 'One or more files to import');
        $this->addOption('save', null, InputOption::VALUE_NONE, 'Save converted records');
    }

    protected function dot(bool $finished = false) : void {
        $this->n++;
        if ($this->save && ($finished || 0 === $this->n % 100)) {
            $this->em->flush();
            $this->em->clear();
            echo "\r{$this->n}";
        }
        if ($finished) {
            echo " - finished\n";
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

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $this->em->getConnection()->getConfiguration()->setSQLLogger();
        $this->save = $input->getOption('save');
        $recordCount = 234043; // manually set.
        foreach ($input->getArgument('path') as $path) {
            echo "\n{$path}\n";
            $file = new File();
            $file->file($path);
            $record = $file->next();
            // @phpstan-ignore-next-line
            while ($record) {
                $ldr = new EstcMarc();
                $ldr->setTitleId($recordCount);
                $ldr->setField('ldr');
                $ldr->setFieldData($record->ldr);
                $this->save($ldr);

                foreach ($record->fields() as $fields) {
                    foreach ($fields as $field) {
                        if ($field->is_control) {
                            $ctrl = new EstcMarc();
                            $ctrl->setTitleId($recordCount);
                            $ctrl->setField($field->tagno);
                            $ctrl->setFieldData($field->data);
                            $this->save($ctrl);
                        }

                        foreach ($field->subfields as $subfield => $value) {
                            $estc = new EstcMarc();
                            $estc->setTitleId($recordCount);
                            $estc->setField($field->tagno);
                            $estc->setSubfield($subfield);
                            $estc->setFieldData($value);
                            $this->save($estc);
                            $this->dot();
                        }
                    }
                }
                $recordCount++;

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
}
