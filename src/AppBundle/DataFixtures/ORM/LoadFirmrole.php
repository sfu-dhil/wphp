<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Firmrole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFirmrole form.
 */
class LoadFirmrole extends Fixture {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Firmrole();
            $fixture->setName('Name ' . $i);

            $em->persist($fixture);
            $this->setReference('firmrole.' . $i, $fixture);
        }

        $em->flush();
    }

}
