<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Currency();
            $fixture->setCode('AB' . chr(64 + $i));
            $fixture->setName('Name ' . $i);
            $fixture->setSymbol(chr(0xA2 + $i));
            $fixture->setDescription("<p>This is paragraph ${i}</p>");
            $em->persist($fixture);
            $this->setReference('currency.' . $i, $fixture);
        }
        $em->flush();
    }
}
