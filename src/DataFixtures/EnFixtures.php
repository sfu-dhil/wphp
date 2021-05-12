<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\En;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some English Novel test data.
 */
class EnFixtures extends Fixture implements FixtureGroupInterface {
    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return ['test'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new En();
            $fixture->setEnId('en-' . $i);
            $fixture->setYear((int) (1800 + $i));
            $fixture->setAuthor('Author ' . $i);
            $fixture->setEditor('Editor ' . $i);
            $fixture->setTranslator('Translator ' . $i);
            $fixture->setTitle('Title ' . $i);
            $fixture->setPublishPlace('Place ' . $i);
            $fixture->setImprint('Imprint ' . $i);
            $fixture->setPagination(($i + 5) . 'pp');
            $fixture->setFormat('folio');
            $fixture->setPrice(($i + 1) . 'p');
            $fixture->setContemporary('Contemporary ' . $i);
            $fixture->setShelfmark('QA 76.' . $i);
            $fixture->setEditions($i + 1);
            $fixture->setGenre('Genre ' . $i);
            $fixture->setNotes('Lorem Ipsum');
            $manager->persist($fixture);
            $this->setReference('en.' . $i, $fixture);
        }
        $manager->flush();
    }
}
