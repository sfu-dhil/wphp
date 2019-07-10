<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Geonames;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some test Geonames data.
 */
class LoadGeonames extends Fixture implements FixtureGroupInterface
{

    /**
     * {@inheritDoc}
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Geonames();
            $fixture->setGeonameid($i);
            $fixture->setName('Name ' . $i);
            $fixture->setAsciiname('Asciiname ' . $i);
            $fixture->setAlternatenames('Alternatenames ' . $i);
            $fixture->setLatitude(40 + $i / 10);
            $fixture->setLongitude(50 + $i / 10);
            $fixture->setFclass('F');
            $fixture->setFcode('Fcode ' . $i);
            $fixture->setCountry('C' . $i);
            $fixture->setCc2('Cc2 ' . $i);
            $fixture->setAdmin1('Admin1 ' . $i);
            $fixture->setAdmin2('Admin2 ' . $i);
            $fixture->setAdmin3('Admin3 ' . $i);
            $fixture->setAdmin4('Admin4 ' . $i);
            $fixture->setPopulation(($i + 1) * 10000);
            $fixture->setElevation($i);
            $fixture->setGtopo30($i);
            $fixture->setTimezone('Z+' . $i);
            $fixture->setModdate(new DateTime());

            $manager->persist($fixture);
            $this->setReference('geonames.' . $i, $fixture);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return array('test');
    }
}
