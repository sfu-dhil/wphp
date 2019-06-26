<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Source;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some test sources.
 */
class LoadSource extends Fixture implements FixtureGroupInterface{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Source();
            $fixture->setName('Name ' . $i);

            $manager->persist($fixture);
            $this->setReference('source.' . $i, $fixture);
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
