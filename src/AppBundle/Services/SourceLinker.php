<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;

use AppBundle\Entity\En;
use AppBundle\Entity\EstcMarc;
use AppBundle\Entity\Jackson;
use AppBundle\Entity\OrlandoBiblio;
use AppBundle\Entity\OsborneMarc;
use AppBundle\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Description of SourceLinker
 *
 * @author mjoyce
 */
class SourceLinker {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generator) {
        $this->em = $em;
        $this->generator = $generator;
    }

    public function estc($data) {
        $repo = $this->em->getRepository(EstcMarc::class);
        $record = $repo->findOneBy(array(
            'fieldData' => $data,
            'field' => '009',
        ));
        if ($record) {
            return $this->generator->generate('resource_estc_show', array(
                        'id' => $record->getTitleId(),
            ));
        }
        return null;
    }

    public function orlando($data) {
        $repo = $this->em->getRepository(OrlandoBiblio::class);
        $record = $repo->findOneBy(array(
            'orlandoId' => $data,
        ));
        if ($record) {
            return $this->generator->generate('resource_orlando_biblio_show', array(
                        'id' => $record->getId(),
            ));
        }
        return null;
    }

    public function jackson($data) {
        $repo = $this->em->getRepository(Jackson::class);
        $record = $repo->findOneBy(array(
            'jbid' => $data,
        ));
        if ($record) {
            return $this->generator->generate('resource_jackson_show', array(
                        'id' => $record->getId(),
            ));
        }
        return null;
    }

    public function en($data) {
        $repo = $this->em->getRepository(En::class);
        $record = $repo->findOneBy(array(
            'enId' => $data,
        ));
        if ($record) {
            return $this->generator->generate('resource_en_show', array(
                        'id' => $record->getId(),
            ));
        }
        return null;
    }

    public function osborne($data) {
        $repo = $this->em->getRepository(OsborneMarc::class);
        $record = $repo->findOneBy(array(
            'fieldData' => $data,
            'field' => '001',
        ));
        if ($record) {
            return $this->generator->generate('resource_osborne_show', array(
                        'id' => $record->getTitleId(),
            ));
        }
        return null;
    }

    public function ecco($data){
        $id = substr_replace($data, "0", 2, 0);
        return "http://link.galegroup.com/apps/doc/{$id}/ECCO?sid=WomenPrintHistProject";
    }

    public function url(Source $source, $data) {
        if (preg_match("/https?:/", $data)) {
            return $data;
        }
        switch ($source->getName()) {
            case "ESTC":
                return $this->estc($data);
            case "Orlando":
                return $this->orlando($data);
            case "Jackson Bibliography":
                return $this->jackson($data);
            case "The English Novel 1770-1829":
            case "The English Novel 1830-1836":
                return $this->en($data);
            case "Osborne Collection of Early Children's Books":
                return $this->osborne($data);
            case 'ECCO':
                return $this->ecco($data);
            default:
                return null;
        }
    }

}
