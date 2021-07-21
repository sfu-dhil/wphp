<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return ['test'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
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
            $fixture->setModdate(new DateTimeImmutable());

            $manager->persist($fixture);
            $this->setReference('geonames.' . $i, $fixture);
        }

        $manager->flush();
    }
}
