<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\AasMarc;
use App\Entity\EstcMarc;
use App\Entity\MarcSubfieldStructure;
use App\Entity\MarcTagStructure;
use App\Entity\OsborneMarc;
use App\Entity\Source;
use App\Entity\TitleSource;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Manage MARC records.
 */
class MarcManager {
    /**
     * MarcManager constructor.
     */
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Get the title of a MARC record.
     *
     * @param EstcMarc|OsborneMarc $object
     */
    public function getTitle($object) : string {
        $repo = $this->em->getRepository($object::class);
        $rows = $repo->findBy([
            'titleId' => $object->getTitleId(),
            'field' => '245',
        ], [
            'field' => 'ASC', 'subfield' => 'ASC',
        ]);

        return implode("\n", array_map(fn ($row) => $row->getFieldData(), $rows));
    }

    /**
     * @param AasMarc|EstcMarc|OsborneMarc $object
     *
     * @return string
     */
    public function getControlId($object) {
        $repo = $this->em->getRepository($object::class);
        $field = $repo->findOneBy([
            'titleId' => $object->getTitleId(),
            'field' => '001',
        ]);

        return $field->getFieldData();
    }

    /**
     * @param EstcMarc|string $record
     *
     * @return bool
     */
    public function isImported($record) {
        $controlId = null;
        if ($record instanceof EstcMarc) {
            $controlId = $this->getControlId($record);
        } elseif (is_string($record)) {
            $controlId = $record;
        }
        // ESTC Source ID is 2.
        $source = $this->em->find(Source::class, 2);
        $repo = $this->em->getRepository(TitleSource::class);
        $ts = $repo->findBy([
            'source' => $source,
            'identifier' => $controlId,
        ]);

        return count($ts) > 0;
    }

    /**
     * Get the author of a MARC record.
     *
     * @param EstcMarc|OsborneMarc $object
     *
     * @return null|string
     */
    public function getAuthor($object) {
        $repo = $this->em->getRepository($object::class);
        $rows = $repo->findBy([
            'titleId' => $object->getTitleId(),
            'field' => '100',
        ], [
            'field' => 'ASC', 'subfield' => 'ASC',
        ]);
        if (count($rows) > 0) {
            return $rows[0]->getFieldData();
        }

    }

    /**
     * Get a field value for a record.
     *
     * @param EstcMarc|OsborneMarc $object
     * @param string $field
     */
    public function getFieldValues($object, $field) : array {
        $repo = $this->em->getRepository($object::class);
        $rows = $repo->findBy([
            'titleId' => $object->getTitleId(),
            'field' => $field,
        ], [
            'field' => 'ASC', 'subfield' => 'ASC',
        ]);

        return array_map(fn ($row) => $row->getFieldData(), $rows);
    }

    /**
     * Get the rows of a MARC record.
     *
     * @param EstcMarc|OsborneMarc $object
     *
     * @return EstcMarc[]|OsborneMarc[]
     */
    public function getData($object) {
        $repo = $this->em->getRepository($object::class);

        return $repo->findBy(['titleId' => $object->getTitleId()], ['field' => 'ASC', 'subfield' => 'ASC']);
    }

    /**
     * Find the name of a MARC field.
     *
     * @param EstcMarc|OsborneMarc $field
     *
     * @return string
     */
    public function getFieldName($field) {
        if ($field->getSubfield()) {
            $repo = $this->em->getRepository(MarcSubfieldStructure::class);
            $tag = $repo->findOneBy([
                'tagField' => $field->getField(),
                'tagSubfield' => $field->getSubfield(),
            ]);
            if ($tag) {
                return $tag->getName();
            }

            return $field->getField() . $field->getSubfield();
        }
        $repo = $this->em->getRepository(MarcTagStructure::class);
        $tag = $repo->findOneBy([
            'tagField' => $field->getField(),
        ]);
        if ($tag) {
            return $tag->getName();
        }

        return $field->getField();
    }
}
