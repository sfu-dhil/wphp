<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\AasMarc;
use Doctrine\ORM\EntityManagerInterface;
use PhpMarc\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportAasCommand extends Command {
    public const AAS = 99;

    private EntityManagerInterface $em;

    protected static $defaultName = 'wphp:import:aas';

    protected static string $defaultDescription = 'Add a short description for your command';

    protected function configure() : void {
        $this->setDescription(self::$defaultDescription);
        $this->addArgument('path', InputArgument::IS_ARRAY, 'One or more files to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $recordCount = 1;
        $n = 0;
        foreach ($input->getArgument('path') as $path) {
            echo "\n{$path}\n";
            $file = new File();
            $file->file($path);
            // @phpstan-ignore-next-line
            while (($record = $file->next())) {
                $ldr = new AasMarc();
                $ldr->setTitleId($recordCount);
                $ldr->setField('ldr');
                $ldr->setFieldData($record->ldr);
                $this->em->persist($ldr);

                foreach ($record->fields() as $fields) {
                    foreach ($fields as $field) {
                        if ($field->is_control) {
                            $ctrl = new AasMarc();
                            $ctrl->setTitleId($recordCount);
                            $ctrl->setField($field->tagno);
                            $ctrl->setFieldData($field->data);
                            $this->em->persist($ctrl);
                        }

                        foreach ($field->subfields as $subfield => $value) {
                            $aas = new AasMarc();
                            $aas->setTitleId($recordCount);
                            $aas->setField($field->tagno);
                            $aas->setSubfield($subfield);
                            $aas->setFieldData($value);
                            $this->em->persist($aas);
                            $n++;
                            if (0 === $n % 20) {
                                $this->em->flush();
                                $this->em->clear();
                                echo "\r{$recordCount} - {$n}";
                            }
                        }
                    }
                }
                $recordCount++;
            }
        }
        $this->em->flush();
        $this->em->clear();
        echo "\r{$recordCount} - {$n}\n";

        return 0;
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }
}
