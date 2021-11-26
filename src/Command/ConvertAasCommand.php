<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\Format;
use App\Entity\Genre;
use App\Entity\Person;
use App\Entity\Role;
use App\Entity\Title;
use App\Entity\TitleRole;
use App\Entity\TitleSource;
use App\Repository\FormatRepository;
use App\Repository\GenreRepository;
use App\Repository\PersonRepository;
use App\Repository\RoleRepository;
use App\Repository\SourceRepository;
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

class ConvertAasCommand extends Command {
    public const AAS = 99;

    public const NAME_PUNCT = [
        '/(\w\w).$/u' => '$1',
        '/,$/u' => '',
    ];

    public const FORMATS = '/\((fo|6mo|4to|6mo|8vo|12mo|16mo|18mo|24mo|32mo|48mo|64mo|bs)\)/u';

    public const BATCH = 25;

    private bool $save = false;

    private EntityManagerInterface $em;

    private int $n = 0;

    private PersonRepository $personRepository;

    /**
     * @var string[]
     */
    private array $names;

    private RoleRepository $roleRepository;

    private FormatRepository $formatRepository;

    private GenreRepository $genreRepository;

    private SourceRepository $sourceRepository;

    protected static $defaultName = 'wphp:convert:aas';

    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure() : void {
        $this->setDescription(self::$defaultDescription);
        $this->addArgument('path', InputArgument::IS_ARRAY, 'One or more files to import');
        $this->addOption('save', null, InputOption::VALUE_NONE, 'Save converted records');
        $this->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Limit the number of converted records', 0);
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
     * Read the list of women's names and titles.
     */
    protected function getNames() : void {
        $data = file_get_contents('data/women.txt');
        $data = preg_split("/\n/u", $data);
        $data = array_filter($data);
        $data = array_map(fn($s) => preg_replace('/^\s+|\s+$/u', '', $s), $data);
        $data = array_map(fn($s) => mb_convert_case($s, MB_CASE_LOWER), $data);
        $this->names = $data;
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
     * Take a MARC role (eg "publisher.") from a field and find or create
     * the corresponding database Role object ("Publisher").
     */
    protected function findRole(string $field) : Role {
        if ( ! $field) {
            $field = 'Contributor';
        }
        $rolePart = preg_replace('/[[:punct:]]*$/u', '', $field);
        $name = mb_convert_case($rolePart, MB_CASE_TITLE);
        $role = $this->roleRepository->findOneBy(['name' => $name]);
        if ( ! $role) {
            $role = new Role();
            $role->setName($name);
            $this->save($role);
        }

        return $role;
    }

    /**
     * Parse the format abbreviation ("4to") from a physical description.
     * [2], vi, 335, [1] p., [11] leaves of plates :$bill., port. ;$c28 cm (4to).
     *
     * The parenthesis seem optional.
     */
    protected function parseFormat(string $field) : ?Format {
        if ( ! $field) {
            return null;
        }
        $m = [];
        if (preg_match(self::FORMATS, $field, $m)) {
            return $this->formatRepository->findOneBy(['abbreviation' => $m[1]]);
        }

        return null;
    }

    /**
     * Join the title subfields intelligently to produce a complete title.
     */
    protected function parseTitle(Field $field) : string {
        $title = '';
        foreach ($field->subfields() as $subfield) {
            $part = preg_replace('/\s*[[:punct:]]*$/u', '', $subfield);
            $title .= $part . ' ';
        }

        return preg_replace('/\s*$/u', '', $title);
    }

    /**
     * Parse $field for a person and role, then add them as a contributor
     * to the title.
     *
     * Field 100 is always considered to be an author.
     * Field 700 is a pseudonym if ind1 is 0.
     * Field 700 is a person if ind1 is not zero. The role is expressed in subfield e.
     */
    protected function addTitleRole(Title $title, ?Field $field) : void {
        if ( ! $field) {
            return;
        }
        if (700 === $field->tagno && '0' === $field->ind1) {
            $title->setPseudonym($field->subfield('a'));

            return;
        }

        $person = $this->parsePerson($field);
        $role = null;
        if (100 !== $field->tagno) {
            if ( ! $field->subfield('e')) {
                $role = $this->findRole('Contributor');
            } else {
                $role = $this->findRole($field->subfield('e'));
            }
        } else {
            $role = $this->findRole('Author');
        }
        if ($title->hasTitleRole($person, $role)) {
            return;
        }
        $titleRole = new TitleRole();
        $titleRole->setPerson($person);
        $titleRole->setRole($role);
        $titleRole->setTitle($title);
        $title->addTitleRole($titleRole);
        $this->save($titleRole);
    }

    /**
     * Parse the genres from $field and add them to the title. Only genres from
     * the 'rbgenr' thesaurus are used. Terminal punctuation is removed.
     */
    protected function addGenres(Title $title, Field $field) : void {
        if ('rbgenr' !== $field->subfield('2')) {
            return;
        }
        $name = preg_replace('/[[:punct:]]*$/', '', $field->subfield('a'));
        $genre = $this->genreRepository->findOneBy(['name' => $name]);
        if ( ! $genre) {
            $genre = new Genre();
            $genre->setName($name);
            $this->save($genre);
        }
        $title->addGenre($genre);
    }

    /**
     * Add AAS as the source of the title data.
     */
    protected function addTitleSource(Title $title, Record $record) : void {
        $source = $this->sourceRepository->find(75);
        $titleSource = new TitleSource();
        $titleSource->setTitle($title);
        $titleSource->setIdentifier($record->field('001')->data());
        $titleSource->setSource($source);
        $title->addTitleSource($titleSource);
        $this->save($titleSource);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->save = $input->getOption('save');
        $limit = $input->getOption('limit');
        $this->getNames();
        $n = 0;
        foreach ($input->getArgument('path') as $path) {
            echo "\n{$path}\n";
            $file = new File();
            $file->file($path);
            while (($record = $file->next())) {
                try {
                    $this->processRecord($record);
                    $this->dot();
                } catch (Exception $e) {
                    $output->writeln("\nError processing record " . $this->n . ' id:' . $record->field('001')->data());
                    $output->writeln($e->getMessage());

                    return 1;
                }
                if ($limit && $this->n >= $limit) {
                    break 2; // break out of the while and for loops
                }
            }
        }
        $this->dot(true);

        return 0;
    }

    protected function findPerson($lastName, $firstName, $title, $birthYear, $deathYear) : Person {
        if ( ! $title) {
            $title = null;
        }
        $criteria = [
            'lastName' => $lastName,
            'firstName' => $firstName,
            'dob' => $birthYear,
            'dod' => $deathYear,
        ];
        $criteria = array_filter($criteria, fn($v) => (bool) $v);
        $person = $this->personRepository->findOneBy($criteria);
        if ( ! $person) {
            $person = new Person();
            $person->setLastName($lastName);
            $person->setFirstName($firstName);
            $person->setTitle($title);
            $person->setDob($birthYear);
            $person->setDod($deathYear);
            if ($firstName && in_array(mb_convert_case($firstName, MB_CASE_LOWER), $this->names, true)) {
                $person->setGender(Person::FEMALE);
            }
            if ($title && in_array(mb_convert_case($title, MB_CASE_LOWER), $this->names, true)) {
                $person->setGender(Person::FEMALE);
            }
            $this->save($person);
        }

        return $person;
    }

    protected function parsePerson($field) : ?Person {
        if ( ! $field || 0 === $field->ind1) {
            return null;
        }
        $dob = '';
        $dod = '';

        $fullName = preg_replace(array_keys(self::NAME_PUNCT), array_values(self::NAME_PUNCT), $field->subfield('a'));
        $nameParts = preg_split('/,\\s*/u', $fullName, 2);
        if ($field->subfield('q')) {
            $nameParts[1] = preg_replace('/^\\(|\\),?$/u', '', $field->subfield('q'));
        }
        if (($datePart = $field->subfield('d'))) {
            $m = [];
            preg_match('/(\d\d\d\d)-/u', $datePart, $m);
            $dob = $m[1] ?? null;
            preg_match('/-(?:[\w\s]+)?(\d\d\d\d)/u', $datePart, $m);
            $dod = $m[1] ?? null;
        }
        $title = $field->subfield('c');

        return $this->findPerson($nameParts[0] ?? '', $nameParts[1] ?? '', $title, $dob, $dod);
    }

    /**
     * Add all 5xx fields as notes and set 500 $a as the copyright if it starts
     * with the word copyright.
     */
    protected function addNotes(Title $title, Record $record) : void {
        if (isset($record->fields[500])) {
            foreach ($record->fields[500] as $field) {
                if ($general = $field->subfield('a')) {
                    if (preg_match('/^copyright/ui', $general)) {
                        $title->setCopyright($general);
                    }
                }
            }
        }

        $notes = [];
        foreach ($record->fields as $fields) {
            foreach ($fields as $field) {
                if ('5' === mb_substr($field->tagno, 0, 1)) {
                    $notes[] = $field->subfield('a');
                }
            }
        }
        $title->setNotes(implode("\n", $notes));
    }

    /**
     * Add the physical description of the book. 300 $a is the pagination
     * and 300 $c is checked for dimensions and format.
     */
    protected function addDescription(Title $title, Record $record) : void {
        if ($physicalDesc = $record->field('300')) {
            if ($extent = $physicalDesc->subfield('a')) {
                $title->setPagination($extent);
            }
            if ($dimensions = $physicalDesc->subfield('c')) {
                $title->setFormat($this->parseFormat($dimensions));
                $m = [];
                if (preg_match('/(\\d+)\\s*x(\\d+)\\s*cm/u', $dimensions, $m)) {
                    $title->setSizeL($m[1]);
                    $title->setSizeW($m[2]);
                } else {
                    if (preg_match('/(\\d+)\\s*cm/u', $dimensions, $m)) {
                        $title->setSizeL($m[1]);
                    }
                }
            }
        }
    }

    /**
     * Process a single record.
     *
     * @throws Exception
     */
    protected function processRecord(Record $record) : void {
        $title = new Title();

        $this->addTitleSource($title, $record);

        if ($titleField = $record->field('100')) {
            $this->addTitleRole($title, $titleField);
        }

        $title->setTitle($this->parseTitle($record->field('245')));

        if ($editionDesc = $record->field('250')) {
            $title->setEdition(preg_replace('/[[:punct:]]*$/', '', $editionDesc->subfield('c')));
        }

        $imprintDesc = $record->field('260');
        if ($imprintDesc) {
            $title->setImprint(preg_replace('/[[:punct:]]*$/', '', $imprintDesc->subfield('b')));
        }

        $this->addDescription($title, $record);

        $this->addNotes($title, $record);

        if (isset($record->fields[655])) {
            foreach ($record->fields[655] as $field) {
                $this->addGenres($title, $field);
            }
        }

        if (isset($record->fields[700])) {
            foreach ($record->fields[700] as $field) {
                $this->addTitleRole($title, $field);
            }
        }
        $this->save($title);
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
    public function setPersonRepository(PersonRepository $personRepository) : void {
        $this->personRepository = $personRepository;
    }

    /**
     * @required
     */
    public function setRoleRepository(RoleRepository $roleRepository) : void {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @required
     */
    public function setFormatRepository(FormatRepository $formatRepository) : void {
        $this->formatRepository = $formatRepository;
    }

    /**
     * @required
     */
    public function setGenreRepository(GenreRepository $genreRepository) : void {
        $this->genreRepository = $genreRepository;
    }

    /**
     * @required
     */
    public function setSourceRepository(SourceRepository $sourceRepository) : void {
        $this->sourceRepository = $sourceRepository;
    }
}
