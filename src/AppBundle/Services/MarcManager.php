<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;

use AppBundle\Entity\MarcSubfieldStructure;
use AppBundle\Entity\MarcTagStructure;
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

    public function getData($object) {
        $repo = $this->em->getRepository(get_class($object));
        $rows = $repo->findBy(array('titleId' => $object->getTitleId()), array('field' => 'ASC', 'subfield' => 'ASC'));
        return $rows;
    }

    public function getFieldName($field) {
        dump($field);
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
            $tag = $repo->findOneBy(array('tagField' => $field));
            if($tag) {
                return $tag->getName();
            } else {
                return $field->getField();
            }
        }
    }

}
