<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TitleFirmrole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test firm roles.
 */
class TitleFirmroleFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $tfr = new TitleFirmrole();
            $tfr->setFirm($this->getReference('firm.' . $i));
            $tfr->setTitle($this->getReference('title.' . $i));
            $tfr->setFirmrole($this->getReference('firmrole.' . $i));

            $manager->persist($tfr);
            $this->setReference('tfr.' . $i, $tfr);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            TitleFixtures::class,
            FirmFixtures::class,
            FirmroleFixtures::class,
        ];
    }
}
