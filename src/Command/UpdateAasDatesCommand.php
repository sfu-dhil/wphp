<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\AasMarc;
use App\Entity\Source;
use App\Entity\Title;
use App\Entity\TitleSource;
use App\Repository\AasMarcRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'wphp:update:aas-dates')]
class UpdateAasDatesCommand extends Command {
    public function __construct(
        private EntityManagerInterface $em,
        private AasMarcRepository $aasRepo,
        private int $n = 0,
        private $save = false,
    ) {
        parent::__construct(null);
    }

    protected function configure() : void {
        $this->setDescription('Update the imported AAS records dates');
        $this->addOption('save', null, InputOption::VALUE_NONE, 'Save changes to database');
    }

    protected function dot(bool $finished = false) : void {
        if ($this->save) {
            $this->em->flush();
        }
        $this->em->clear();
        if ($finished) {
            echo "\nfinished\n";
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->save = $input->getOption('save');
        $dql = <<<'ENDQUERY'
                SELECT t FROM App\Entity\Title t
            ENDQUERY;
        $query = $this->em->createQuery($dql);

        // Iterate over ever title because Doctrine doesn't iterate well with
        // MEMBER OF queries.
        foreach ($query->toIterable() as $title) {
            /** @var Title $title */

            // don't mangle existing pubDates.
            if ($title->getPubdate()) {
                $this->dot();

                continue;
            }

            // search for the title source. 75 is the database ID for AAS.
            $titleSource = null;
            foreach ($title->getTitleSources() as $ts) {
                /** @var TitleSource $ts */
                if (75 === $ts->getSource()->getId()) {
                    $titleSource = $ts;
                }
            }
            if ( ! $titleSource) {
                $this->dot();

                continue;
            }

            // find the AASMarc title ID (stored as `cid` in the database)
            /** @var AasMarc $marc001 */
            $marc001 = $this->aasRepo->findOneBy([
                'field' => '001',
                'fieldData' => $titleSource->getIdentifier(),
            ]);
            $titleId = $marc001->getTitleId();

            // Use the title ID to find the date
            /** @var AasMarc $marc260c */
            $marc260c = $this->aasRepo->findOneBy([
                'field' => '260',
                'subfield' => 'c',
                'titleId' => $titleId,
            ]);
            if ( ! $marc260c) {
                $output->writeln("Cannot find AAS pubdate for {$title->getId()}");
                $this->dot();

                continue;
            }
            $dateField = $marc260c->getFieldData();
            $matches = [];
            if ( ! preg_match('/(\d{4})/', $dateField, $matches)) {
                $output->writeln("Cannot find year for {$title->getId()} in {$dateField}");
                $this->dot();

                continue;
            }
            $pubDate = $matches[1];
            $title->setPubDate($pubDate);
            $output->writeln("Setting date for {$title->getId()} to {$pubDate}");
            $this->dot();
        }
        $this->dot(true);

        return 0;
    }
}
