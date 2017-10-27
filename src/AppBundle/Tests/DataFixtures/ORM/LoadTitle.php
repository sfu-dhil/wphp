<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Title;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of LoadTitle
 *
 * @author dogan
 */
class LoadTitle extends AbstractDataFixture {
    
    protected function doLoad(ObjectManager $manager) {
        
        
       $title = new Title();
       
       $title->setTitle("Demolition Man");
       $title->setSignedAuthor("John Author");
       $title->setNotes("This is a note");
       $title->setFinalcheck(true);
       
        
        
        $manager->persist($title);
        $manager->flush();
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
