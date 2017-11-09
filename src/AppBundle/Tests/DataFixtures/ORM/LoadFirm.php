<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Geonames;
use AppBundle\Entity\Firm;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of LoadFirm
 *
 * @author dogan
 */
class LoadFirm extends AbstractDataFixture {
    
    protected function doLoad(ObjectManager $manager) {
        $firm = new Firm();
        
        
        $firm->setName("Great Firm");
        $firm->setStreetAddress("Great Place");
        
        $firm->setStartDate("1988");
        $firm->setEndDate("2088");
        $firm->setFinalcheck('1');
            
        $manager->persist($firm);
        $manager->flush();
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
