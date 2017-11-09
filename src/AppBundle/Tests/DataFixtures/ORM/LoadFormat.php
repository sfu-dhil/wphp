<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Format;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadFormat
 *
 * @author dogan
 */
class LoadFormat extends AbstractDataFixture {
    
    protected function doLoad(ObjectManager $manager) {
        $format = new Format();
        $format->setName("Great Name");
        
        $manager->persist($format);
        $manager->flush();
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
