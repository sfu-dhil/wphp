<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Format;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test format data.
 */
class FormatFixtures extends Fixture implements FixtureGroupInterface {
    public const DATA = [
        ['folio', 'fo'],
        ['quarto', '4to'],
        ['sexto', '6to'],
        ['octavo', '8vo'],
        ['duodecimo', '12mo'],
        ['sextodecimo', '16mo'],
        ['octodecimo', '18mo'],
        ['vicesimo-quarto', '24mo'],
        ['trigesimo-secundo', '32mo'],
        ['quadragesimo-octavo', '48mo'],
        ['sexagesimo-quarto', '64mo'],
        ['broadside', 'bs'],
    ];

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
