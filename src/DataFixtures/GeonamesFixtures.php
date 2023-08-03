<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Geonames;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test Geonames data.
 */
class GeonamesFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Geonames();
            $fixture->setGeonameid($i);
            $fixture->setName('Name ' . $i);
            $fixture->setAsciiname('Asciiname ' . $i);
            $fixture->setAlternatenames('Alternatenames ' . $i);
            $fixture->setLatitude(sprintf('%f', 40 + $i / 10));
            $fixture->setLongitude(sprintf('%f', 50 + $i / 10));
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
            $fixture->setModdate(new DateTimeImmutable());

            $manager->persist($fixture);
            $this->setReference('geonames.' . $i, $fixture);
        }

        $manager->flush();
    }
}
