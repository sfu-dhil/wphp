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
        
        
       $title1 = new Title();
       
       $title1->setTitle("Demolition Man");
       $title1->setSignedAuthor("John Author");
       $title1->setNotes("This is a note");
       $title1->setChecked('1');
       $title1->setFinalcheck('1');
       $title1->setSelfpublished('0');
       $title1->setLocationOfPrinting($this->getReference('geonames.1'));
       $title1->setFormat($this->getReference('format.1'));
       $title1->setGenre($this->getReference('genre.1'));
       $title1->setSource($this->getReference('source.1'));
       
       $this->setReference('title.1', $title1);       
       $manager->persist($title1);
       
       $title2 = new Title();
       
       $title2->setTitle("Asparagus");
       $title2->setSignedAuthor("Billy Author");
       
       $this->setReference('title.2', $title2);       
       $manager->persist($title2);
       
       
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
