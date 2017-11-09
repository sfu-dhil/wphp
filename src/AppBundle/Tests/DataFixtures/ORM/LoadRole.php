<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Role;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of LoadRole
 *
 * @author dogan
 */
class LoadRole extends AbstractDataFixture {
   
    protected function doLoad(ObjectManager $manager) {
        $role = new Role();
        $role->setName("Some Role");
        
        $manager->persist($role);
        $manager->flush();
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
