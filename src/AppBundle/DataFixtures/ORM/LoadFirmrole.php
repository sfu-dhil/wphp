<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Firmrole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some test firm roles.
 */
class LoadFirmrole extends Fixture implements FixtureGroupInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Firmrole();
            $fixture->setName('Name ' . $i);

            $manager->persist($fixture);
            $this->setReference('firmrole.' . $i, $fixture);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array {
        return array('test');
    }
}
