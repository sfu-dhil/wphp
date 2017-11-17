<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Firmrole;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadFirmrole
 *
 * @author dogan
 */
class LoadFirmrole extends AbstractDataFixture {
    
     protected function doLoad(ObjectManager $manager) {
        $firmrole = new Firmrole();
        $firmrole->setName("Publisher");
        $this->setReference('firmrole.1', $firmrole);
        
        $manager->persist($firmrole);
        $manager->flush();
    }
    

    protected function getEnvironments() {
        return ['test'];
    }
}
