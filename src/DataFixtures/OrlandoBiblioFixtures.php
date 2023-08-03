<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\OrlandoBiblio;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test Orlando data.
 */
class OrlandoBiblioFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new OrlandoBiblio();
            $fixture->setOrlandoId($i + 100);
            $fixture->setWorkform('work form ' . $i);
            $fixture->setAuthor("A_ID = 20384 || STANDARD = Author {$i} || ROLE = EDITOR %%% A_ID = 19884 || STANDARD = Other Author {$i} || ROLE = AUTHOR");
            $fixture->setAnalyticStandardTitle('Title ' . $i);
            $fixture->setMonographicStandardTitle('Title ' . $i);
            $fixture->setImprintDateOfPublication('1880');
            $manager->persist($fixture);
            $this->setReference('orlando.' . $i, $fixture);
        }
        $manager->flush();
    }
}
