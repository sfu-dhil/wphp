<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Firm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test firms.
 */
class FirmFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Firm();
            $fixture->setName('Name ' . $i);
            $fixture->setStreetAddress('StreetAddress ' . $i);
            $fixture->setStartDate(sprintf('%d', 1800 - $i * 10));
            $fixture->setEndDate(sprintf('%d', 1820 + $i * 15));
            $fixture->setFinalcheck(0 === $i % 2);
            $fixture->setGender('U');
            $fixture->setCity($this->getReference('geonames.1'));
            $fixture->setNotes("<p>Firm Notes {$i}</p>");
            $manager->persist($fixture);
            $this->setReference('firm.' . $i, $fixture);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            GeonamesFixtures::class,
        ];
    }
}
