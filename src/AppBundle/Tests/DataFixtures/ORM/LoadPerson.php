<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Person;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadPerson
 *
 * @author dogan
 */
class LoadPerson extends AbstractDataFixture {
    
    protected function doLoad(ObjectManager $manager) {
        
        $person = new Person();
        $person->setFirstName("Bobby");
        $person->setLastName("Rock");
        $person->setGender("M");
        $person->setTitle("Bobby the Man");
        $person->setDob("1952-02-02");
        $person->setDod("1982-02-02");
        $person->setChecked('1');
        $person->setFinalcheck('1');
        
        $manager->persist($person);
        $manager->flush();
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
