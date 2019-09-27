<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TitleFirmrole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load some test firm roles.
 */
class LoadTitleFirmrole extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{

    /**
     * {@inheritDoc}
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
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

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return array(
            LoadTitle::class,
            LoadFirm::class,
            LoadFirmrole::class,
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return array('test');
    }
}
