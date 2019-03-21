<?php

namespace AppBundle\Services;

use AppBundle\Entity\MarcSubfieldStructure;
use AppBundle\Entity\MarcTagStructure;
use AppBundle\Entity\OsborneMarc;
use AppBundle\Repository\EstcMarcRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of MarcManager
 *
 * @author michael
 */
class MarcManager {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * Get the title of a MARC record.
     *
     * @param EstcMarc | OsborneMarc $object
     * @return string
     */
    public function getTitle($object) {
        $repo = $this->em->getRepository(get_class($object));
        $rows = $repo->findBy(array(
            'titleId' => $object->getTitleId(),
            'field' => '245',
        ), array(
            'field' => 'ASC', 'subfield' => 'ASC'
        ));
        return implode("\n", array_map(function($row){return $row->getFieldData();}, $rows));
    }

    /**
     * Get the author of a MARC record.
     *
     * @param EstcMarc | OsborneMarc $object
     * @return string
     */
    public function getAuthor($object) {
        $repo = $this->em->getRepository(get_class($object));
        $rows = $repo->findBy(array(
            'titleId' => $object->getTitleId(),
            'field' => '100',
        ), array(
            'field' => 'ASC', 'subfield' => 'ASC'
        ));
        if(count($rows) > 0) {
            return $rows[0]->getFieldData();
        }
        return null;
    }

    /**
     * Get the rows of a MARC record.
     *
     * @param EstcMarc | OsborneMarc $object
     * @return Collection|EstcMarc[]|OsborneMarc[]
     */
    public function getData($object) {
        $repo = $this->em->getRepository(get_class($object));
        $rows = $repo->findBy(array('titleId' => $object->getTitleId()), array('field' => 'ASC', 'subfield' => 'ASC'));
        return $rows;
    }

    /**
     * Find the name of a MARC field.
     *
     * @param EstcMarc|OsborneMarc $field
     * @return string
     */
    public function getFieldName($field) {
        if($field->getSubfield()) {
            $repo = $this->em->getRepository(MarcSubfieldStructure::class);
            $tag = $repo->findOneBy(array(
                'tagField' => $field->getField(),
                'tagSubfield' => $field->getSubfield(),
            ));
            if($tag) {
                return $tag->getName();
            } else {
                return $field->getField() . $field->getSubfield();
            }
        } else {
            $repo = $this->em->getRepository(MarcTagStructure::class);
            $tag = $repo->findOneBy(array(
                'tagField' => $field->getField()
            ));
            if($tag) {
                return $tag->getName();
            } else {
                return $field->getField();
            }
        }
    }

}
