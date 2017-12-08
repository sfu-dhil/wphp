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
        
        $firm1 = new Firm();
        
        $firm1->setName("Great Firm");
        $firm1->setStreetAddress("Great Place");
        $firm1->setStartDate("1988-01-01");
        $firm1->setEndDate("2088-01-01");
        $firm1->setFinalcheck('1');
        $firm1->setCity($this->getReference('geonames.1'));
        $this->setReference('firm.1', $firm1);
        $manager->persist($firm1);
        
        // @todo move this to a new fixture.
        $role = new TitleFirmrole();
        $role->setFirm($firm1);
        $role->setFirmrole($this->getReference('firmrole.1'));
        $role->setTitle($this->getReference('title.1'));
        
        $manager->persist($role);
        
        $firm2 = new Firm();        
        $firm2->setName("Greater Firm");
        $firm2->setStreetAddress("Greater Place");
        $firm2->setStartDate("1988-01-01");
        $firm2->setEndDate("2088-01-01");
        $firm2->setFinalcheck('1');
        $firm2->setCity($this->getReference('geonames.1'));
        $this->setReference('firm.2', $firm2);
        $manager->persist($firm2);

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
