<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\EstcMarc;
use App\Entity\Source;
use App\Entity\TitleSource;
use App\Services\MarcManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'wphp:update:estc')]
class UpdateEstcCommand extends Command {
    final public const BATCH_SIZE = 100;

    final public const ESTC_ID = 2;

    public function __construct(
        private EntityManagerInterface $em,
        private MarcManager $manager,
    ) {
        parent::__construct(null);
    }

    protected function configure() : void {
        $this->setDescription('Change the ESTC Identifiers from 009 to 001');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void {
        $qb = $this->em->createQueryBuilder();
        $source = $this->em->find(Source::class, self::ESTC_ID);
        $qb->select('ts')->from(TitleSource::class, 'ts')->where('ts.source = :source')->setParameter('source', $source);
        $iterator = $qb->getQuery()->iterate();
        while ($row = $iterator->next()) {
            /** @var TitleSource $titleSource */
            $titleSource = $row[0];
            $estcMarc = $this->em->getRepository(EstcMarc::class)->findOneBy([
                'field' => '009',
                'fieldData' => $titleSource->getIdentifier() . '\\',
            ]);
            if ( ! $estcMarc) {
                continue;
            }
            $newId = $this->manager->getFieldValues($estcMarc, '001');

            $titleSource->setIdentifier($newId[0]);
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
