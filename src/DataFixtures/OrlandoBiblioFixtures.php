<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\OrlandoBiblio;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test Orlando data.
 */
class OrlandoBiblioFixtures extends Fixture implements FixtureGroupInterface {
    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return ['test', 'orlando'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new OrlandoBiblio();
            $fixture->setOrlandoId($i + 100);
            $fixture->setWorkform('work form ' . $i);
            $fixture->setAuthor("A_ID = 20384 || STANDARD = Author {$i} || ROLE = EDITOR %%% A_ID = 19884 || STANDARD = Other Author {$i} || ROLE = AUTHOR");
            $fixture->setAnalyticStandardTitle('Title ' . $i);
            $fixture->setMonographicStandardTitle('Title ' . $i);
            $fixture->setImprintDateOfPublication('1880');
            $manager->persist($fixture);
            $this->setReference('orlando.' . $i, $fixture);
        }
        $manager->flush();
    }
}
