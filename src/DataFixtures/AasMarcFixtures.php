<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\AasMarc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test AAS MARC data.
 */
class AasMarcFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($n = 0; $n < 4; $n++) {
            $ldr = new AasMarc();
            $ldr->setTitleId(1 + $n);
            $ldr->setField('ldr');
            $ldr->setFieldData('aas-leader-' . $n);
            $manager->persist($ldr);

            $id = new AasMarc();
            $id->setTitleId(1 + $n);
            $id->setField('001');
            $id->setFieldData('abc-' . $n);
            $manager->persist($id);

            $title = new AasMarc();
            $title->setTitleId(1 + $n);
            $title->setField('245');
            $title->setSubfield('a');
            $title->setFieldData('AAS Title ' . $n);
            $manager->persist($title);

            $title = new AasMarc();
            $title->setTitleId(1 + $n);
            $title->setField('260');
            $title->setSubfield('b');
            $title->setFieldData('AAS Imprint ' . $n);
            $manager->persist($title);

            for ($j = 0; $j < 20; $j++) {
                for ($i = 0; $i < 10; $i++) {
                    $fixture = new AasMarc();
                    $fixture->setTitleId(1 + $n);
                    $fixture->setField(sprintf('%d', 100 + $j));
                    $fixture->setSubfield('abcdefghijklmnop'[$i]);
                    $fixture->setFieldData("Aas Field Data {$n} " . (100 + $j) . 'abcdefghijklmnop'[$i]);
                    $manager->persist($fixture);
                    $this->setReference("aas.{$n}.{$j}.{$i}", $fixture);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            MarcTagStructureFixtures::class,
            MarcSubfieldStructureFixtures::class,
        ];
    }
}
