<?php

namespace App\DataFixtures;

use App\Entity\TitleRelationship;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TitleRelationshipFixtures extends Fixture {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new TitleRelationship();
            $fixture->setName('Name ' . $i);
            $fixture->setLabel('Label ' . $i);
            $fixture->setDescription("<p>This is paragraph ${i}</p>");
            $em->persist($fixture);
            $this->setReference('titlerelationship.' . $i, $fixture);
        }
        $em->flush();
    }

}
