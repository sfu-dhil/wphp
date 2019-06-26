<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TitleSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some test title sources.
 */
class LoadTitleSource extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {

    /**
     * {@inheritDoc}
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        for ($i = 0; $i < 4; $i++) {
            $title = $this->getReference('title.' . $i);
            for($j = 0; $j < 2; $j++) {
                $fixture = new TitleSource();
                $fixture->setTitle($title);
                $fixture->setSource($this->getReference('source.' . $j));
                $fixture->setIdentifier('http://example.com/id/' . $i . '/' . $j);
                $manager->persist($fixture);
                $this->em->setReference('titlesource.' . $i);
            }
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            LoadTitle::class,
            LoadSource::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array {
        return array('test');
    }
}
