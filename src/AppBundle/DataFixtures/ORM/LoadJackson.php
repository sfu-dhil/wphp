<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Jackson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadJackson extends Fixture implements FixtureGroupInterface {

    public function load(ObjectManager $manager) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Jackson();
            $fixture->setJbid(1234 + $i);
            $fixture->setAuthor('Author ' . $i);
            $fixture->setTitle('Title ' . $i);
            $fixture->setDetailedEntry('Entry ' . $i);
            $fixture->setExamnote('Note ' . $i);
            $manager->persist($fixture);
            $this->setReference('jackson.' . $i, $fixture);
        }
        $manager->flush();
    }

    public static function getGroups(): array {
        return array('test');
    }
}
