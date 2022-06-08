<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Currency();
            $fixture->setCode('AB' . chr(64 + $i));
            $fixture->setName('Name ' . $i);
            $fixture->setSymbol(chr(64 + $i));
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $em->persist($fixture);
            $this->setReference('currency.' . $i, $fixture);
        }
        $em->flush();
    }
}
