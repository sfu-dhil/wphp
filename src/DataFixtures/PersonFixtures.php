<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test persons.
 */
class PersonFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Person();
            $fixture->setLastName('LastName ' . $i);
            $fixture->setFirstName('FirstName ' . $i);
            $fixture->setTitle('TitleName ' . $i);
            $fixture->setGender('F');
            $fixture->setDob((1750 + $i) . '-01-02');
            $fixture->setDod((1800 + $i) . '-03-05');
            $fixture->setFinalcheck(0 === $i % 2);
            $fixture->setCityofbirth($this->getReference('geonames.1'));
            $fixture->setCityofdeath($this->getReference('geonames.1'));
            $fixture->setNotes("<p>Person Notes {$i}</p>");

            $manager->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            GeonamesFixtures::class,
        ];
    }
}
