<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some test persons.
 */
class LoadPerson extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
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

            $manager->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return array(
            LoadGeonames::class,
        );
    }
}
