<?php

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
        return array('test');
    }

    /**
     * {@inheritdoc}
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
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
