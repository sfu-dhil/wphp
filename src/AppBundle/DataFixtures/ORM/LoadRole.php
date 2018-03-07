<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadRole form.
 */
class LoadRole extends Fixture {

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

}
