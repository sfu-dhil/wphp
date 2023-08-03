<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test role data.
 */
class RoleFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Role();
            $fixture->setName('Name ' . $i);

            $manager->persist($fixture);
            $this->setReference('role.' . $i, $fixture);
        }

        $fixture = new Role();
        $fixture->setName('Author');
        $manager->persist($fixture);

        $manager->flush();
    }
}
