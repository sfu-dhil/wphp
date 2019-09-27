<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some test role data.
 */
class LoadRole extends Fixture implements FixtureGroupInterface
{

    /**
     * {@inheritDoc}
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
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

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return array('test');
    }
}
