<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MarcTagStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMarcTagStructure extends Fixture implements FixtureGroupInterface 
{

    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 20; $i++) {
            $fixture = new MarcTagStructure();
            $fixture->setName('Tag ' . $i);
            $fixture->setTagField(100+$i);
            $em->persist($fixture);
            $this->setReference('marctag.' . $i, $fixture);
        }
        $em->flush();
    }
    
    public static function getGroups(): array {
        return array('test');
    }
}
