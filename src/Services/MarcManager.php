<?php

namespace App\Services;

use App\Entity\MarcSubfieldStructure;
use App\Entity\MarcTagStructure;
use App\Entity\OsborneMarc;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Manage MARC records.
 */
class MarcManager {
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * MarcManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * Get the title of a MARC record.
     *
     * @param EstcMarc | OsborneMarc $object
     *
     * @return string
     */
    public function getTitle($object) {
        $repo = $this->em->getRepository(get_class($object));
        $rows = $repo->findBy(array(
            'titleId' => $object->getTitleId(),
            'field' => '245',
        ), array(
            'field' => 'ASC', 'subfield' => 'ASC',
        ));

        return implode("\n", array_map(function ($row) {return $row->getFieldData(); }, $rows));
    }

    /**
     * Get the author of a MARC record.
     *
     * @param EstcMarc | OsborneMarc $object
     *
     * @return string
     */
    public function getAuthor($object) {
        $repo = $this->em->getRepository(get_class($object));
        $rows = $repo->findBy(array(
            'titleId' => $object->getTitleId(),
            'field' => '100',
        ), array(
            'field' => 'ASC', 'subfield' => 'ASC',
        ));
        if (count($rows) > 0) {
            return $rows[0]->getFieldData();
        }
    }

    /**
     * Get a field value for a record.
     *
     * @param EstcMarc|OsborneMarc $object
     * @param string $field
     *
     * @return array
     */
    public function getFieldValues($object, $field) {
        $repo = $this->em->getRepository(get_class($object));
        $rows = $repo->findBy(array(
            'titleId' => $object->getTitleId(),
            'field' => $field,
        ), array(
            'field' => 'ASC', 'subfield' => 'ASC',
        ));

        return array_map(function ($row) {
            return $row->getFieldData();
        }, $rows);
    }

    /**
     * Get the rows of a MARC record.
     *
     * @param EstcMarc | OsborneMarc $object
     *
     * @return Collection|EstcMarc[]|OsborneMarc[]
     */
    public function getData($object) {
        $repo = $this->em->getRepository(get_class($object));

        return $repo->findBy(array('titleId' => $object->getTitleId()), array('field' => 'ASC', 'subfield' => 'ASC'));
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
            $tag = $repo->findOneBy(array(
                'tagField' => $field->getField(),
                'tagSubfield' => $field->getSubfield(),
            ));
            if ($tag) {
                return $tag->getName();
            }

            return $field->getField() . $field->getSubfield();
        }
        $repo = $this->em->getRepository(MarcTagStructure::class);
        $tag = $repo->findOneBy(array(
            'tagField' => $field->getField(),
        ));
        if ($tag) {
            return $tag->getName();
        }

        return $field->getField();
    }
}
