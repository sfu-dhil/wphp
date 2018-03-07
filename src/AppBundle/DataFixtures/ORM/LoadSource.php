<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Source;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSource form.
 */
class LoadSource extends Fixture {

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

}
