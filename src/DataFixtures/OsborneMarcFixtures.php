<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\OsborneMarc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test Osborne MARC data.
 */
class OsborneMarcFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($n = 0; $n < 4; $n++) {
            $ldr = new OsborneMarc();
            $ldr->setTitleId(1 + $n);
            $ldr->setField('ldr');
            $ldr->setFieldData('osborne-leader-' . $n);
            $manager->persist($ldr);

            $id = new OsborneMarc();
            $id->setTitleId(1 + $n);
            $id->setField('001');
            $id->setFieldData('abc-' . $n);
            $manager->persist($id);

            $title = new OsborneMarc();
            $title->setTitleId(1 + $n);
            $title->setField('245');
            $title->setSubfield('a');
            $title->setFieldData('OSBORNE Title ' . $n);
            $manager->persist($title);

            for ($j = 0; $j < 20; $j++) {
                for ($i = 0; $i < 10; $i++) {
                    $fixture = new OsborneMarc();
                    $fixture->setField(sprintf('%d', 100 + $j));
                    $fixture->setSubfield('abcdefghijklmnop'[$i]);
                    $fixture->setTitleId(1 + $n);
                    $fixture->setFieldData("Osborne Field Data {$n} {$j} {$i}");
                    $manager->persist($fixture);
                    $this->setReference("osborne.{$n}.{$j}.{$i}", $fixture);
                }
            }
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            MarcTagStructureFixtures::class,
            MarcSubfieldStructureFixtures::class,
        ];
    }
}
