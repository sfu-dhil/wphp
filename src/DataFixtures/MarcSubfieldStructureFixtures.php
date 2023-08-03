<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\MarcSubfieldStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test MARC subfield data for testing.
 */
class MarcSubfieldStructureFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($j = 0; $j < 20; $j++) {
            for ($i = 0; $i < 10; $i++) {
                $subfield = 'abcdefghijklmnop'[$i];
                $fixture = new MarcSubfieldStructure();
                $fixture->setTagField(sprintf('%d', 100 + $j));
                $fixture->setTagSubfield($subfield);
                $fixture->setName('Field ' . (100 + $j) . $subfield);
                $fixture->setHidden(0 === $i % 4);
                $manager->persist($fixture);
                $this->setReference("marcsubfield.{$j}.{$i}", $fixture);
            }
        }
        $manager->flush();
    }
}
