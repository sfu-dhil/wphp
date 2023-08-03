<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\MarcTagStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some MARC tag definitions for testing.
 */
class MarcTagStructureFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 20; $i++) {
            $fixture = new MarcTagStructure();
            $fixture->setName('Tag ' . $i);
            $fixture->setTagField(sprintf('%d', 100 + $i));
            $manager->persist($fixture);
            $this->setReference('marctag.' . $i, $fixture);
        }
        $manager->flush();
    }
}
