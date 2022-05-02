<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services;

use App\Entity\EstcMarc;
use App\Entity\Format;
use App\Entity\Person;
use App\Entity\Role;
use App\Entity\Source;
use App\Entity\Title;
use App\Entity\TitleRole;
use App\Entity\TitleSource;
use App\Repository\EstcMarcRepository;
use App\Repository\FormatRepository;
use App\Repository\PersonRepository;
use App\Repository\RoleRepository;
use App\Repository\SourceRepository;
use App\Repository\TitleRepository;
use App\Repository\TitleSourceRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Import MARC records from ESTC.
 */
class EstcMarcImporter {
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EstcMarcRepository
     */
    private $estcRepo;

    /**
     * @var TitleRepository
     */
    private $titleRepo;

    /**
     * @var RoleRepository
     */
    private $roleRepo;

    /**
     * @var SourceRepository
     */
    private $sourceRepo;

    /**
     * @var PersonRepository
     */
    private $personRepo;

    /**
     * @var FormatRepository
     */
    private $formatRepo;

    /**
     * @var TitleSourceRepository
     */
    private $titleSourceRepository;

    /**
     * @var array
     */
    private $messages;

    /**
     * EstcMarcImporter constructor.
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->estcRepo = $this->em->getRepository(EstcMarc::class);
        $this->titleRepo = $this->em->getRepository(Title::class);
        $this->roleRepo = $this->em->getRepository(Role::class);
        $this->sourceRepo = $this->em->getRepository(Source::class);
        $this->personRepo = $this->em->getRepository(Person::class);
        $this->formatRepo = $this->em->getRepository(Format::class);
        $this->titleSourceRepository = $this->em->getRepository(TitleSource::class);
        $this->messages = [];
    }

    /**
     * Find the MARC fields for an ID.
     *
     * @param string $id
     *
     * @return array
     */
    public function getFields($id) {
        $data = $this->estcRepo->findBy(['titleId' => $id]);
        $fields = [];

        foreach ($data as $row) {
            $fields[$row->getField() . $row->getSubfield()] = $row;
        }

        return $fields;
    }

    /**
     * Check if the title has already been imported.
     *
     * @param string $fullTitle
     * @param string $id
     */
    public function checkTitle($fullTitle, $id) : void {
        if (count($this->titleRepo->findBy(['title' => $fullTitle]))) {
            $this->messages[] = 'This title may already exist in the database. Please check for it before saving the form.';
        }

        if (count($this->titleSourceRepository->findBy([
            'source' => $this->sourceRepo->findOneBy(['name' => 'ESTC']),
            'identifier' => $id,
        ]))) {
            $this->messages[] = 'This ESTC ID already exists in the database. Please check that you are not duplicating data.';
        }
    }

    /**
     * Attempt to fetch a person record based on a MARC record.
     *
     * @param array $fields
     *
     * @return Person[]
     */
    public function getPerson($fields) {
        if ( ! isset($fields['100a'])) {
            return [];
        }
        $fullName = preg_replace('/[^a-zA-Z0-9]*$/', '', $fields['100a']->getFieldData());
        if (mb_strpos($fullName, ',')) {
            list($last, $first) = explode(', ', $fullName);
            list($dob, $dod) = $this->getDates($fields);

            return $this->personRepo->findByNameDates($first, $last, $dob, $dod);
        }

        return [];
    }

    /**
     * Get the dates of a publication. Dates are not entered consistently in the ESTC, so success will vary.
     *
     * @param array $fields
     *
     * @return array
     */
    public function getDates($fields) {
        if ( ! isset($fields['100d']) || null === $fields['100d']->getFieldData()) {
            return [null, null];
        }
        $data = $fields['100d']->getFieldData();

        $matches = [];
        if (preg_match('/(\d{4})[?.]*-(\d{4})/', $data, $matches)) {
            return [$matches[1], $matches[2]];
        }
        if (preg_match('/-(\d{4})/', $data, $matches)) {
            return [null, $matches[1]];
        }
        if (preg_match('/(\d{4})[?.]*-/', $data, $matches)) {
            return [$matches[1], null];
        }
        if (preg_match('/b\.\s*(\d{4})/', $data, $matches)) {
            return [$matches[1], null];
        }

        $this->messages[] = 'Cannot parse author dates: ' . $data . '. Author information may be incorrect.';

        return [null, null];
    }

    /**
     * Guess the format of a title.
     *
     * @param array $fields
     *
     * @return null|Format
     */
    public function guessFormat($fields) {
        if ( ! isset($fields['300c']) || null === $fields['300c']->getFieldData()) {
            return null;
        }
        $data = $fields['300c']->getFieldData();
        $matches = [];
        $format = null;
        if (preg_match('/(\d+[mtv]o)/', $data, $matches)) {
            $format = $this->formatRepo->findOneBy([
                'abbreviation' => $matches[1],
            ]);
        }
        if ( ! $format) {
            $this->messages[] = 'Cannot guess format from ' . $data . '.';
            $format = $this->formatRepo->findOneBy([
                'name' => 'unknown',
            ]);
        }

        return $format;
    }

    /**
     * Guess the dimensions of a title.
     *
     * @param array $fields
     *
     * @return array
     */
    public function guessDimensions($fields) {
        if ( ! isset($fields['300c']) || null === $fields['300c']->getFieldData()) {
            return [null, null];
        }
        $data = $fields['300c']->getFieldData();
        $matches = [];
        if (preg_match('/(\\d+)\\s*(?:cm)?\\s*x\\s*(\\d+)\\s*(?:cm)?/', $data, $matches)) {
            return [$matches[1], $matches[2]];
        }

        if (preg_match('/(\\d+)\\s*(?:cm|mm)/', $data, $matches)) {
            return [$matches[1], null];
        }

        $this->messages[] = 'Cannot parse dimensions: ' . $data;

        return [null, null];
    }

    /**
     * Import one title. Does not persist the title.
     *
     * @param string $id
     *
     * @return Title
     */
    public function import($id) {
        $fields = $this->getFields($id);

        $fullTitle = $fields['245a']->getFieldData();
        if (isset($fields['245b'])) {
            $fullTitle .= ' ' . $fields['245b']->getFieldData();
        }
        $this->checkTitle($fullTitle, $fields['001']->getFieldData());

        $title = new Title();
        $title->setTitle($fullTitle);

        if (isset($fields['300a'])) {
            $title->setPagination($fields['300a']->getFieldData());
        }
        if (isset($fields['260b'])) {
            $title->setImprint($fields['260b']->getFieldData());
        }
        if (isset($fields['260c'])) {
            $title->setPubdate(preg_replace('/\\.$/', '', $fields['260c']->getFieldData()));
        }

        $titleSource = new TitleSource();
        $titleSource->setSource($this->sourceRepo->findOneBy(['name' => 'ESTC']));
        $titleSource->setTitle($title);
        $titleSource->setIdentifier($fields['001']->getFieldData());
        $title->addTitleSource($titleSource);

        $format = $this->guessFormat($fields);
        $title->setFormat($format);

        $authors = $this->getPerson($fields);
        if (count($authors) > 0) {
            $this->addAuthor($title, $authors[0]);
        }

        list($width, $height) = $this->guessDimensions($fields);
        $title->setSizeL($width);
        $title->setSizeW($height);
        $title->setChecked(false);

        return $title;
    }

    /**
     * Add the person to a title as an author.
     *
     * @param ?Person $person
     */
    public function addAuthor(Title $title, ?Person $person) : void {
        if ( ! $person) {
            return;
        }
        $role = $this->roleRepo->findOneBy(['name' => 'Author']);
        $titleRole = new TitleRole();
        $titleRole->setPerson($person);
        $titleRole->setRole($role);
        $titleRole->setTitle($title);
        $title->addTitleRole($titleRole);
        $this->em->persist($titleRole);
    }

    /**
     * Get the list of messages generated during the import.
     *
     * @return mixed
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * Clear out the messages list.
     */
    public function resetMessages() : void {
        $this->messages = [];
    }

    public function setEstcRepo(EstcMarcRepository $estcRepo) : void {
        $this->estcRepo = $estcRepo;
    }

    public function setTitleRepo(TitleRepository $titleRepo) : void {
        $this->titleRepo = $titleRepo;
    }

    public function setTitleSourceRepo(TitleSourceRepository $titleSourceRepo) : void {
        $this->titleSourceRepository = $titleSourceRepo;
    }

    public function setRoleRepo(RoleRepository $roleRepo) : void {
        $this->roleRepo = $roleRepo;
    }

    public function setSourceRepo(SourceRepository $sourceRepo) : void {
        $this->sourceRepo = $sourceRepo;
    }

    public function setFormatRepo(FormatRepository $formatRepo) : void {
        $this->formatRepo = $formatRepo;
    }
}
