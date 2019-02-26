<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TitleFirmrole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFirmrole form.
 */
class LoadTitleFirmrole extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $tfr = new TitleFirmrole();
            $tfr->setFirm($this->getReference('firm.' . $i));
            $tfr->setTitle($this->getReference('title.' . $i));
            $tfr->setFirmrole($this->getReference('firmrole.' . $i));

            $em->persist($tfr);
            $this->setReference('tfr.' . $i, $tfr);
        }

        $em->flush();
    }

    public function getDependencies() {
        return array(
            LoadTitle::class,
            LoadFirm::class,
            LoadFirmrole::class,
        );
    }

    public static function getGroups(): array {
        return array('test');
    }
}
