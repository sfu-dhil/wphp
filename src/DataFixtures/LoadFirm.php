<?php

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
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Firm();
            $fixture->setName('Name ' . $i);
            $fixture->setStreetAddress('StreetAddress ' . $i);
            $fixture->setStartDate(1800 - $i * 10);
            $fixture->setEndDate(1820 + $i * 15);
            $fixture->setFinalcheck(0 === $i % 2);
            $fixture->setGender('U');
            $fixture->setCity($this->getReference('geonames.1'));

            $manager->persist($fixture);
            $this->setReference('firm.' . $i, $fixture);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return array(
            GeonamesFixtures::class,
        );
    }
}
