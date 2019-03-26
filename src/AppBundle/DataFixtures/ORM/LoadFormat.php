<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Format;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFormat form.
 */
class LoadFormat extends Fixture implements FixtureGroupInterface {

    const DATA = array(
        ["folio","fo","2°","2°","2⁰"],
        ["quarto","4to","4°","4°","4⁰"],
        ["sexto","6to", "6mo","6°","6°","6⁰"],
        ["octavo","8vo","8°","8°","8⁰"],
        ["duodecimo","12mo","12°","12°","12⁰"],
        ["sextodecimo","16mo","16°","16°","16⁰"],
        ["octodecimo","18mo","18°","18°","18⁰"],
        ["vicesimo-quarto","24mo","24°","24°","24⁰"],
        ["trigesimo-secundo","32mo","32°","32°","32⁰"],
        ["quadragesimo-octavo","48mo","48°","48°","48⁰"],
        ["sexagesimo-quarto","64mo","64°","64°","64⁰"],
        ["broadside","bs","1°","1°","1⁰"],
    );

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

        foreach(self::DATA as $row) {
            $fixture = new Format();
            $fixture->setName($row[0]);
            $fixture->setAbbrevOne($row[1]);
            $fixture->setAbbrevTwo($row[2]);
            $fixture->setAbbrevThree($row[3]);
            $fixture->setAbbrevFour($row[4]);
            $em->persist($fixture);
        }

        $em->flush();
    }

    public static function getGroups(): array {
        return array('test');
    }
}
