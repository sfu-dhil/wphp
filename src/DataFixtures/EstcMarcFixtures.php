<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\EstcMarc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test ESTC MARC data.
 */
class EstcMarcFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
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
        for ($n = 0; $n < 4; $n++) {
            $ldr = new EstcMarc();
            $ldr->setTitleId(1 + $n);
            $ldr->setField('ldr');
            $ldr->setFieldData('estc-leader-' . $n);
            $manager->persist($ldr);

            $id = new EstcMarc();
            $id->setTitleId(1 + $n);
            $id->setField('001');
            $id->setFieldData('abc-' . $n);
            $manager->persist($id);

            $title = new EstcMarc();
            $title->setTitleId(1 + $n);
            $title->setField('245');
            $title->setSubfield('a');
            $title->setFieldData('ESTC Title ' . $n);
            $manager->persist($title);

            for ($j = 0; $j < 20; $j++) {
                for ($i = 0; $i < 10; $i++) {
                    $fixture = new EstcMarc();
                    $fixture->setTitleId(1 + $n);
                    $fixture->setField(100 + $j);
                    $fixture->setSubfield('abcdefghijklmnop'[$i]);
                    $fixture->setFieldData("Estc Field Data {$n} " . (100 + $j) . 'abcdefghijklmnop'[$i]);
                    $manager->persist($fixture);
                    $this->setReference("estc.{$n}.{$j}.{$i}", $fixture);
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
