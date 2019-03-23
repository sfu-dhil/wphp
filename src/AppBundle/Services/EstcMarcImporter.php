<?php

namespace AppBundle\Services;

use AppBundle\Entity\Title;
use AppBundle\Repository\EstcMarcRepository;
use Doctrine\ORM\EntityManagerInterface;

class EstcMarcImporter {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MarcManager
     */
    private $manager;

    private $estcRepo;

    public function __construct(EntityManagerInterface $em, MarcManager $manager, EstcMarcRepository $estcRepo) {
        $this->em = $em;
        $this->manager = $manager;
        $this->estcRepo = $estcRepo;
        dump($this);
    }

    public function import($type, $id) {
        $data = $this->estcRepo->findBy(array('titleId' => $id));
        $fields = array();
        foreach($data as $row) {
            $fields[$row->getField() . $row->getSubfield()] = $row;
        }
        dump($fields);
        $title = new Title();
        $title->setTitle($fields['245a']->getFieldData() . " " . $fields['245b']->getFieldData());
        $title->setPagination($fields['300a']->getFieldData());
        $title->setImprint($fields['260b']->getFieldData());
        $title->setDateOfFirstPublication($fields['260c']->getFieldData());

        return $title;
    }


}
