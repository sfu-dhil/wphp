<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\EstcMarc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadEstcMarc extends Fixture implements DependentFixtureInterface
{
// INSERT INTO `estc_fields` (`cid`, `fid`, `field`, `ind1`, `ind2`, `subfield`, `field_data`, `id`) VALUES (196740,15,'533','\\','\\','n','Access limited by licensing agreements.',7158756)
    public function load(ObjectManager $manager) {
        for($n = 0; $n < 4; $n++) {
            $ldr = new EstcMarc();
            $ldr->setTitleId(1 + $n);
            $ldr->setField('ldr');
            $ldr->setFieldData('estc-leader-' . $n);
            $manager->persist($ldr);

            $title = new EstcMarc();
            $title->setTitleId(1 + $n);
            $title->setField('245');
            $title->setSubfield('a');
            $title->setFieldData('ESTC Title ' . $n);
            $manager->persist($title);

            for($j = 0; $j < 20; $j++) {
                for ($i = 0; $i < 10; $i++) {
                    $fixture = new EstcMarc();
                    $fixture->setField(100 + $j);
                    $fixture->setSubfield('abcdefghijklmnop'[$i]);
                    $fixture->setTitleId(1 + $n);
                    $fixture->setFieldData("Estc Field Data $n " . (100 + $j) . 'abcdefghijklmnop'[$i]);
                    $manager->persist($fixture);
                    $this->setReference("estc.$n.$j.$i", $fixture);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            LoadMarcTagStructure::class,
            LoadMarcSubfieldStructure::class,
        ];
    }

}
