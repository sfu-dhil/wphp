<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Geonames;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of LoadGeonames
 *
 * @author dogan
 */
class LoadGeonames extends AbstractDataFixture {
    
    protected function doLoad(ObjectManager $manager) {
        $geonames = new Geonames();
        
        $geonames->setName("Some Name");
        $geonames->setCountry("Some Country");
        $geonames->setElevation("12321312");
        $geonames->setPopulation("12312");
        
        $manager->persist($geonames);
        $manager->flush();
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
