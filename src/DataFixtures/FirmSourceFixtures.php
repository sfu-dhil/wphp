<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\FirmSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test firm sources.
 */
class FirmSourceFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
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
            $firm = $this->getReference('firm.' . $i);

            for ($j = 0; $j < 2; $j++) {
                $fixture = new FirmSource();
                $fixture->setFirm($firm);
                $fixture->setSource($this->getReference('source.' . $j));
                $fixture->setIdentifier('http://example.com/id/' . $i . '/' . $j);
                $manager->persist($fixture);
                $this->setReference('firmsource.' . $i, $fixture);
            }
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            FirmFixtures::class,
            SourceFixtures::class,
        ];
    }
}
