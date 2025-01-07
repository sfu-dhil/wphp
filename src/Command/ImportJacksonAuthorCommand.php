<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Person;
use App\Repository\TitleSourceRepository;
use App\Repository\SourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'wphp:import:jackson_author')]
class ImportJacksonAuthorCommand extends Command {
    public function __construct(
        private EntityManagerInterface $em,
        private TitleSourceRepository $titleSourceRepository,
        private SourceRepository $sourceRepository,
    ) {
        parent::__construct();
    }

    protected function configure() : void {
        $this->setDescription('Import Jackson Author links');
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'jackson title & author csv file'
        );
    }

    protected function getAuthorUrlByName(Person $person, array $jacksonAuthors) : ?string {
        $firstName = $person->getFirstName();
        $firstNameInitial = $firstName[0] ?? '';
        $lastName = $person->getLastName();
        foreach($jacksonAuthors as $jacksonAuthor) {
            $authorUrl = $jacksonAuthor['url'];
            // easy matches
            if ($jacksonAuthor['name'] === "{$lastName}, {$firstName}") {
                return $authorUrl;
            } else if ($jacksonAuthor['name'] === "{$lastName}, {$firstNameInitial}.") {
                return $authorUrl;
            } else if ($jacksonAuthor['name'] === $lastName) {
                return $authorUrl;
            }

            // harder matches
            // - match on if the jackson last name is somewhere in wphp last name
            //   and at least one part of jackson first name is somewhere in wphp first name
            //   ex: WPHP `Taylor (later Gilbert), Ann` should match `Taylor, Ann`
            //       WPHP `O'Keeffe, Adelaide` should match `O'Keeffe, Adelaide D.`
            //       WPHP `Campbell, Dorothea Primrose` should match `Campbell, Dorothy Primrose`
            $jacksonNameParts = explode(', ', $jacksonAuthor['name']);
            if (count($jacksonNameParts) >= 2 && $lastName && $firstName) {
                $jacksonLastName = $jacksonNameParts[0];
                $jacksonFirstNameParts = explode(' ', $jacksonNameParts[1]);
                if (str_contains($lastName, $jacksonLastName)) {
                    foreach($jacksonFirstNameParts as $jacksonFirstNamePart) {
                        if (str_contains($firstName, $jacksonFirstNamePart)) {
                            return $authorUrl;
                        }
                    }
                    // if there is only one author and the last name matches, then assume its correct
                    if (count($jacksonAuthors) === 1) {
                        return $authorUrl;
                    }
                }
            }
        }
        return null;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $missingRecords = [];
        $nameProblems = [];
        $urlProblems = [];
        $path = $input->getArgument('path');

        $reader = Reader::createFromPath($path);
        $reader->setHeaderOffset(0);
        $jacksonTitleAuthorMap = [];
        foreach ($reader->getRecords() as $record) {
            // $output->writeln("Record: " . print_r($record, true));
            $jacksonTitleId = $record['Romance_Id'];
            $isDeleted = $record['IsDeleted'];
            $authorId = $record['Author_Id'];
            $authorName = $record['Author'];
            $authorUrl = $record['Author Url'];

            // skip is deleted record or missing author record in jackson (no name or url)
            if ($isDeleted || !$authorName || !$authorUrl) {
                continue;
            }
            if (!array_key_exists($jacksonTitleId, $jacksonTitleAuthorMap)) {
                $jacksonTitleAuthorMap[$jacksonTitleId] = [];
            }
            $jacksonTitleAuthorMap[$jacksonTitleId][] = [
                'id' => $authorId,
                'name' => $authorName,
                'url' => $authorUrl,
            ];
        }

        $jacksonSource = $this->sourceRepository->findOneBy([
            'name' => 'Jackson Bibliography'
        ]);
        $jacksonTitleSources = $this->titleSourceRepository->findBy([
            'source' => $jacksonSource,
        ]);
        foreach ($jacksonTitleSources as $jacksonTitleSource) {
            $title = $jacksonTitleSource->getTitle();
            $jacksonTitleId = $jacksonTitleSource->getIdentifier();

            if (!array_key_exists($jacksonTitleId, $jacksonTitleAuthorMap)) {
                $missingRecords[] = <<<EOD
WPHP Title: https://womensprinthistoryproject.com/title/{$title->getId()}
Jackson Title: https://jacksonbibliography.library.utoronto.ca/search/details/{$jacksonTitleId}\n\n
EOD;
                continue;
            }

            $titleAuthors = $title->getTitleRoles('Author');
            $jacksonAuthors = $jacksonTitleAuthorMap[$jacksonTitleId];

            foreach($titleAuthors as $titleAuthor) {
                $person = $titleAuthor->getPerson();

                $authorUrl = $this->getAuthorUrlByName($person, $jacksonAuthors);
                if (!$authorUrl) {
                    if ($person->getJacksonUrl()) {
                        // skip error message is they already have a url
                        continue;
                    }
                    $str = <<<EOD
WPHP Title: https://womensprinthistoryproject.com/title/{$title->getId()}
WPHP Person: https://womensprinthistoryproject.com/person/{$person->getId()}\t`{$person->getLastName()}, {$person->getFirstName()}`
Jackson Title: https://jacksonbibliography.library.utoronto.ca/search/details/{$jacksonTitleId}\n
EOD;
                    foreach($jacksonAuthors as $jacksonAuthor) {
                        $str .= "Jackson Person: {$jacksonAuthor['url']}\t`{$jacksonAuthor['name']}`\n";
                    }
                    $nameProblems[] = $str . "\n";
                    continue;
                }
                if ($person->getJacksonUrl() && $person->getJacksonUrl() !== $authorUrl) {
                    $urlProblems[] = <<<EOD
WPHP Title: https://womensprinthistoryproject.com/title/{$title->getId()}
WPHP Person: https://womensprinthistoryproject.com/person/{$person->getId()}
Jackson Title: https://jacksonbibliography.library.utoronto.ca/search/details/{$jacksonTitleId}
Current Jackson Person: {$person->getJacksonUrl()}
Potential Jackson Person: {$authorUrl}\n\n
EOD;
                    continue;
                } else if (!$person->getJacksonUrl()) {
                    $person->setJacksonUrl($authorUrl);
                    $this->em->persist($person);
                }
            }
        }
        $this->em->flush();

        $missingCount = count($missingRecords);
        if ($missingCount > 0) {
            $output->writeln("\n--------------------------------------------------------------\nMissing Jackson Titles ({$missingCount}): Could not find the jackson source identifier in the supplied CSV\n--------------------------------------------------------------");
            foreach ($missingRecords as $missingRecord) {
                $output->writeln($missingRecord);
            }
        }
        $nameProblemCount = count($nameProblems);
        if ($nameProblemCount > 0) {
            $output->writeln("\n--------------------------------------------------------------\nAuthor Name Mismatch ({$nameProblemCount}):\n--------------------------------------------------------------");
            foreach ($nameProblems as $nameProblem) {
                $output->writeln($nameProblem);
            }
        }
        $urlProblemCount = count($urlProblems);
        if ($urlProblemCount > 0) {
            $output->writeln("\n--------------------------------------------------------------\nMultiple Potential Author Urls ({$urlProblemCount}):\n--------------------------------------------------------------");
            foreach ($urlProblems as $urlProblem) {
                $output->writeln($urlProblem);
            }
        }

        return 0;
    }
}
