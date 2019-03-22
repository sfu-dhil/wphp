<?php

namespace AppBundle\Services;

use AppBundle\Entity\EstcMarc;
use AppBundle\Entity\OsborneMarc;
use AppBundle\Entity\Person;
use AppBundle\Entity\Title;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MarcImporter {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $manager;

    public function __construct(EntityManagerInterface $em, MarcManager $manager) {
        $this->em = $em;
        $this->manager = $manager;
    }

    public function getRepository($type) {
        switch ($type) {
            case 'estc':
                return $this->em->getRepository(EstcMarc::class);
                break;
            case 'osborne':
                return $this->em->getRepository(OsborneMarc::class);
                break;
            default:
                throw new Exception("Unknown MARC source {$type}.");
        }
    }

    public function import($type, $id) {
        $repo = $this->getRepository($type, $id);
        $data = $repo->findBy(array('titleId' => $id));
        $title = new Title();
        $title->setTitle(implode(" ", $this->manager->getFieldValues($data[0], '245')));

        return $title;
    }


}
