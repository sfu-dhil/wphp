<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\EstcMarc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadEstcMarc extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{

    public function load(ObjectManager $manager) {
        for($n = 0; $n < 4; $n++) {
            $ldr = new EstcMarc();
            $ldr->setTitleId(1 + $n);
            $ldr->setField('ldr');
            $ldr->setFieldData('estc-leader-' . $n);
            $manager->persist($ldr);

            $id = new EstcMarc();
            $id->setTitleId(1 + $n);
            $id->setField('009');
            $id->setFieldData('abc-' . $n);
            $manager->persist($id);

            $title = new EstcMarc();
            $title->setTitleId(1 + $n);
            $title->setField('245');
            $title->setSubfield('a');
            $title->setFieldData('ESTC Title ' . $n);
            $manager->persist($title);

            for($j = 0; $j < 20; $j++) {
                for ($i = 0; $i < 10; $i++) {
                    $fixture = new EstcMarc();
                    $fixture->setTitleId(1 + $n);
                    $fixture->setField(100 + $j);
                    $fixture->setSubfield('abcdefghijklmnop'[$i]);
                    $fixture->setFieldData("Estc Field Data $n " . (100 + $j) . 'abcdefghijklmnop'[$i]);
                    $manager->persist($fixture);
                    $this->setReference("estc.$n.$j.$i", $fixture);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            LoadMarcTagStructure::class,
            LoadMarcSubfieldStructure::class,
        ];
    }

    public static function getGroups(): array {
        return array('test');
    }
}
