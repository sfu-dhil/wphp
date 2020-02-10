<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\Title;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * UpdateEditionNumberCommand command.
 */
class UpdateEditionNumberCommand extends Command {
    public const BATCH_SIZE = 100;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UpdateEditionNumberCommand constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Configure the command.
     */
    protected function configure() : void {
        $this
            ->setName('wphp:update:editions')
            ->setDescription('Update title edition number from edition text.')
        ;
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     *                              Command input, as defined in the configure() method.
     * @param OutputInterface $output
     *                                Output destination.
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e')->from(Title::class, 'e')->where('e.edition is not null');
        $iterator = $qb->getQuery()->iterate();
        $matches = [];
        while ($row = $iterator->next()) {
            $title = $row[0];
            if ($title->getEditionNumber()) {
                continue;
            }
            if (preg_match('/^(\d+)/', $title->getEdition(), $matches)) {
                $title->setEditionNumber($matches[1]);
            }
            if (0 === $iterator->key() % self::BATCH_SIZE) {
                $this->em->flush();
                $this->em->clear();
                $output->write("\r" . $iterator->key());
            }
        }
        $this->em->flush();
        $this->em->clear();
        $output->writeln("\rfinished.");
    }
}
