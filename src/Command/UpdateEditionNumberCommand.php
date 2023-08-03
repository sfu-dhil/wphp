<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Title;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'wphp:update:editions')]
class UpdateEditionNumberCommand extends Command {
    final public const BATCH_SIZE = 100;

    public function __construct(
        private EntityManagerInterface $em,
    ) {
        parent::__construct(null);
    }

    protected function configure() : void {
        $this
            ->setDescription('Update title edition number from edition text.')
        ;
    }

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
            if (preg_match('/^(\d+)/', (string) $title->getEdition(), $matches)) {
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
