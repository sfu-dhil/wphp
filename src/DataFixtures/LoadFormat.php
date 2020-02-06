<?php

namespace App\DataFixtures;

use App\Entity\Format;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test format data.
 */
class FormatFixtures extends Fixture implements FixtureGroupInterface {
    const DATA = array(
        array('folio', 'fo'),
        array('quarto', '4to'),
        array('sexto', '6to'),
        array('octavo', '8vo'),
        array('duodecimo', '12mo'),
        array('sextodecimo', '16mo'),
        array('octodecimo', '18mo'),
        array('vicesimo-quarto', '24mo'),
        array('trigesimo-secundo', '32mo'),
        array('quadragesimo-octavo', '48mo'),
        array('sexagesimo-quarto', '64mo'),
        array('broadside', 'bs'),
    );

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
            $fixture = new Format();
            $fixture->setName('Name ' . $i);
            $fixture->setAbbreviation('A' . $i);

            $manager->persist($fixture);
            $this->setReference('format.' . $i, $fixture);
        }

        foreach (self::DATA as $row) {
            $fixture = new Format();
            $fixture->setName($row[0]);
            $fixture->setAbbreviation($row[1]);
            $manager->persist($fixture);
        }

        $manager->flush();
    }
}
