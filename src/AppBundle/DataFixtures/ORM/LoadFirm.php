<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Firm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFirm form.
 */
class LoadFirm extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Firm();
            $fixture->setName('Name ' . $i);
            $fixture->setStreetAddress('StreetAddress ' . $i);
            $fixture->setStartDate(1800 - $i * 10);
            $fixture->setEndDate(1820 + $i * 15);
            $fixture->setFinalcheck($i % 2 === 0);
            $fixture->setGender('U');
            $fixture->setCity($this->getReference('geonames.1'));

            $em->persist($fixture);
            $this->setReference('firm.' . $i, $fixture);
        }

        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return [
            LoadGeonames::class,
        ];
    }

    public static function getGroups(): array {
        return array('test');
    }
}
