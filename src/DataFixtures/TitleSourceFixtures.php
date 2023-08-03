<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TitleSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test title sources.
 */
class TitleSourceFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $title = $this->getReference('title.' . $i);

            for ($j = 0; $j < 2; $j++) {
                $fixture = new TitleSource();
                $fixture->setTitle($title);
                $fixture->setSource($this->getReference('source.' . $j));
                $fixture->setIdentifier('http://example.com/id/' . $i . '/' . $j);
                $manager->persist($fixture);
                $this->setReference('titlesource.' . $i, $fixture);
            }
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            TitleFixtures::class,
            SourceFixtures::class,
        ];
    }
}
