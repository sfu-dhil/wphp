<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Genre;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of LoadGenre
 *
 * @author dogan
 */
class LoadGenre extends AbstractDataFixture  {
    
    
    protected function doLoad(ObjectManager $manager) {
        $genre = new Genre();
        $genre->setName("Novel");
        
        $this->setReference('genre.1', $genre);
        
        $manager->persist($genre);
        $manager->flush();
    }
    

    protected function getEnvironments() {
        return ['test'];
    }
    
}
