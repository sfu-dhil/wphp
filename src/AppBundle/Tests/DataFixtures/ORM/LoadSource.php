<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Source;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadSource
 *
 * @author dogan
 */
class LoadSource extends AbstractDataFixture {
    
    protected function doLoad(ObjectManager $manager) {
        
        $source = new Source();
        $source->setName("Some Name");
        
        $this->setReference('source.1', $source);
                
        $manager->persist($source);
        $manager->flush();
    }
    

    protected function getEnvironments() {
        return ['test'];
    }
}
