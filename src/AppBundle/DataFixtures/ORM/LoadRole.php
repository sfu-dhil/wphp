<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRole form.
 */
class LoadRole extends Fixture implements FixtureGroupInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Role();
            $fixture->setName('Name ' . $i);

            $em->persist($fixture);
            $this->setReference('role.' . $i, $fixture);
        }

        $em->flush();
    }

    public static function getGroups(): array {
        return array('test');
    }
}
