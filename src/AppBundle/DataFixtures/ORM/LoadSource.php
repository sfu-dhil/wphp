<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Source;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSource form.
 */
class LoadSource extends Fixture implements FixtureGroupInterface{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Source();
            $fixture->setName('Name ' . $i);

            $em->persist($fixture);
            $this->setReference('source.' . $i, $fixture);
        }

        $em->flush();
    }

    public static function getGroups(): array {
        return array('test');
    }
}
