<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MarcSubfieldStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMarcSubfieldStructure extends Fixture implements FixtureGroupInterface {

    public function load(ObjectManager $manager) {
        for ($j = 0; $j < 20; $j++) {
            for ($i = 0; $i < 10; $i++) {
                $subfield = 'abcdefghijklmnop'[$i];
                $fixture = new MarcSubfieldStructure();
                $fixture->setTagField(100 + $j);
                $fixture->setTagSubfield($subfield);
                $fixture->setName('Field ' . (100 + $j) . $subfield);
                $fixture->setHidden([0, -6, -5, -1][$i % 4]);
                $manager->persist($fixture);
                $this->setReference("marcsubfield.$j.$i", $fixture);
            }
        }
        $manager->flush();
    }

    public static function getGroups(): array {
        return array('test');
    }

}
