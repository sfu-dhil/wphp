<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Format;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFormat form.
 */
class LoadFormat extends Fixture {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Format();
            $fixture->setName('Name ' . $i);
            $fixture->setAbbrevOne('A' . $i);
            $fixture->setAbbrevTwo('B' . $i);
            $fixture->setAbbrevThree('C' . $i);
            $fixture->setAbbrevFour('D' . $i);

            $em->persist($fixture);
            $this->setReference('format.' . $i, $fixture);
        }

        $em->flush();
    }

}
