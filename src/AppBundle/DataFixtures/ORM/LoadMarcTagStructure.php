<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MarcTagStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some MARC tag definitions for testing.
 */
class LoadMarcTagStructure extends Fixture implements FixtureGroupInterface {
    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return array('test');
    }

    /**
     * {@inheritdoc}
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
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
