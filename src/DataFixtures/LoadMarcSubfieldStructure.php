<?php

namespace App\DataFixtures;

use App\Entity\MarcSubfieldStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test MARC subfield data for testing.
 */
class MarcSubfieldStructureFixtures extends Fixture implements FixtureGroupInterface {
    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return array('test');
    }

    /**
     * {@inheritdoc}
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        for ($j = 0; $j < 20; $j++) {
            for ($i = 0; $i < 10; $i++) {
                $subfield = 'abcdefghijklmnop'[$i];
                $fixture = new MarcSubfieldStructure();
                $fixture->setTagField(100 + $j);
                $fixture->setTagSubfield($subfield);
                $fixture->setName('Field ' . (100 + $j) . $subfield);
                $fixture->setHidden(array(0, -6, -5, -1)[$i % 4]);
                $manager->persist($fixture);
                $this->setReference("marcsubfield.{$j}.{$i}", $fixture);
            }
        }
        $manager->flush();
    }
}
