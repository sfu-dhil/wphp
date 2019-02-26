<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\En;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadEn
 *
 * @author michael
 */
class LoadEn extends Fixture implements FixtureGroupInterface {
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new En();
            $fixture->setEnId("en-" . $i);
            $fixture->setYear((int)(1800 + $i));
            $fixture->setAuthor('Author ' . $i);
            $fixture->setEditor('Editor ' . $i);
            $fixture->setTranslator('Translator ' . $i);
            $fixture->setTitle('Title ' . $i);
            $fixture->setPublishPlace('Place ' . $i);
            $fixture->setImprint('Imprint ' . $i);
            $fixture->setPagination(($i+5) . 'pp');
            $fixture->setFormat('folio');
            $fixture->setPrice(($i+1) . 'p');
            $fixture->setContemporary('Contemporary ' . $i);
            $fixture->setShelfmark('QA 76.' . $i);
            $fixture->setEditions($i+1);
            $fixture->setGenre('Genre ' . $i);
            $fixture->setNotes('Lorem Ipsum');
            $em->persist($fixture);
            $this->setReference('en.' . $i, $fixture);
        }
        $em->flush();
    }

    public static function getGroups(): array {
        return array('test');
    }

}
