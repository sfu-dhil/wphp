<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Person;
use AppBundle\Entity\TitleRole;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadPerson
 *
 * @author dogan
 */
class LoadPerson extends AbstractDataFixture implements DependentFixtureInterface {
    
    protected function doLoad(ObjectManager $manager) {
        
        $person1 = new Person();
        $person1->setFirstName("Bobby");
        $person1->setLastName("Rock");
        $person1->setGender("M");
        $person1->setTitle("Bobby the Man");
        $person1->setDob("1952-02-02");
        $person1->setDod("1982-02-02");
        $person1->setChecked('1');
        $person1->setFinalcheck('1');       
        $manager->persist($person1);
        $this->setReference('person.1', $person1);

        // @todo move this TR to a new fixture.
        $role = new TitleRole();
        $role->setPerson($person1);
        $role->setRole($this->getReference('role.1'));
        $role->setTitle($this->getReference('title.1'));        
        $manager->persist($role);
        
        $person2 = new Person();
        $person2->setFirstName("Shelly");
        $person2->setLastName("Granite");
        $person2->setGender("F");
        $person2->setTitle("Admiral");
        $manager->persist($person2);
        $this->setReference('person.2', $person2);
        
        
        $manager->flush();
    }
    
     public function getDependencies() {
        return [
            LoadGeonames::class,
            LoadRole::class,
            LoadTitle::class
        ];
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
