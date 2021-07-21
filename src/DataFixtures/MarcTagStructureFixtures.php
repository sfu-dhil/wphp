<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\MarcTagStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some MARC tag definitions for testing.
 */
class MarcTagStructureFixtures extends Fixture implements FixtureGroupInterface {
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
        for ($i = 0; $i < 20; $i++) {
            $fixture = new MarcTagStructure();
            $fixture->setName('Tag ' . $i);
            $fixture->setTagField(100 + $i);
            $manager->persist($fixture);
            $this->setReference('marctag.' . $i, $fixture);
        }
        $manager->flush();
    }
}
