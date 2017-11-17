<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Title;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of LoadTitle
 *
 * @author dogan
 */
class LoadTitle extends AbstractDataFixture implements DependentFixtureInterface {
    
    protected function doLoad(ObjectManager $manager) {
        
        
       $title = new Title();
       
       $title->setTitle("Demolition Man");
       $title->setSignedAuthor("John Author");
       $title->setNotes("This is a note");
       $title->setChecked('1');
       $title->setFinalcheck('1');
       $title->setSelfpublished('0');
       $title->setLocationOfPrinting($this->getReference('geonames.1'));
       $title->setFormat($this->getReference('format.1'));
       $title->setGenre($this->getReference('genre.1'));
       $title->setSource($this->getReference('source.1'));
       
       $this->setReference('title.1', $title);
       
       $manager->persist($title);
       $manager->flush();
    }
    
    
    public function getDependencies() {
        return [
            LoadGeonames::class,
            LoadFormat::class,
            LoadGenre::class,
            LoadSource::class
            
        ];
    }

    protected function getEnvironments() {
        return ['test'];
    }
}
