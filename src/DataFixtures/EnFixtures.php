<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\En;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some English Novel test data.
 */
class EnFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

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
            $fixture->setEditions(sprintf('%d', $i + 1));
            $fixture->setGenre('Genre ' . $i);
            $fixture->setNotes('Lorem Ipsum');
            $manager->persist($fixture);
            $this->setReference('en.' . $i, $fixture);
        }
        $manager->flush();
    }
}
