<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\MarcSubfieldStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test MARC subfield data for testing.
 */
class MarcSubfieldStructureFixtures extends Fixture implements FixtureGroupInterface {
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
        for ($j = 0; $j < 20; $j++) {
            for ($i = 0; $i < 10; $i++) {
                $subfield = 'abcdefghijklmnop'[$i];
                $fixture = new MarcSubfieldStructure();
                $fixture->setTagField(100 + $j);
                $fixture->setTagSubfield($subfield);
                $fixture->setName('Field ' . (100 + $j) . $subfield);
                $fixture->setHidden([0, -6, -5, -1][$i % 4]);
                $manager->persist($fixture);
                $this->setReference("marcsubfield.{$j}.{$i}", $fixture);
            }
        }
        $manager->flush();
    }
}
