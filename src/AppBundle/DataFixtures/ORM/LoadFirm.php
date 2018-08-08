<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Firm;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFirm form.
 */
class LoadFirm extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Firm();
            $fixture->setName('Name ' . $i);
            $fixture->setStreetAddress('StreetAddress ' . $i);
            $fixture->setStartDate(new DateTime(1800 - $i * 10));
            $fixture->setEndDate(new DateTime(1820 + $i * 15));
            $fixture->setFinalcheck($i % 2 === 0);
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

}
