<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\Title;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EditionsCommand extends Command {
    public const BATCH_SIZE = 100;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected static $defaultName = 'wphp:check:editions';

    protected static string $defaultDescription = 'Update edition checked field';

    /**
     * UpdateEditionNumberCommand constructor.
     */
    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure() : void {
        $this->setDescription(self::$defaultDescription);
        $this->addArgument('file', InputArgument::REQUIRED, 'CSV file to read');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $toCheck = [];
        $fh = fopen($input->getArgument('file'), 'r');
        $skipped = fgetcsv($fh);
        while (($row = fgetcsv($fh))) {
            if ( ! in_array($row[0], $toCheck, true)) {
                $toCheck[] = $row[0];
            }
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('title')->from(Title::class, 'title');
        $iterator = $qb->getQuery()->iterate();
        $n = 0;
        while (($row = $iterator->next())) {
            /** @var Title $title */
            $title = $row[0];
            if ( ! in_array($title->getEdition(), $toCheck, true)) {
                $title->setEditionChecked(true);
            }
            if (0 === $iterator->key() % self::BATCH_SIZE) {
                $this->em->flush();
                $this->em->clear();
                $output->write("\r" . $iterator->key());
            }
        }

        return 0;
    }
}
