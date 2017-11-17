<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Firm;
use AppBundle\Entity\TitleFirmrole;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of LoadFirm
 *
 * @author dogan
 */
class LoadFirm extends AbstractDataFixture implements DependentFixtureInterface {
    
    
    protected function doLoad(ObjectManager $manager) {
        
        $firm = new Firm();
        
        $firm->setName("Great Firm");
        $firm->setStreetAddress("Great Place");
        $firm->setStartDate("1988-01-01");
        $firm->setEndDate("2088-01-01");
        $firm->setFinalcheck('1');
        $firm->setCity($this->getReference('geonames.1'));
       
        $manager->persist($firm);
        
        $role = new TitleFirmrole();
        $role->setFirm($firm);
        $role->setFirmrole($this->getReference('firmrole.1'));
        $role->setTitle($this->getReference('title.1'));
        
        $manager->persist($role);
        
        $manager->flush();
    }
    
       
    public function getDependencies() {
        return [
            LoadGeonames::class,
            LoadFirmrole::class,
            LoadTitle::class
        ];
    }
    
    protected function getEnvironments() {
        return ['test'];
    }
}
