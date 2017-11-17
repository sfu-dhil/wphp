<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\Entity;
use AppBundle\Entity\Firm;
use AppBundle\Entity\Firmrole;
use AppBundle\Entity\TitleFirmrole;

/**
 * Description of FirmTest
 *
 * @author dogan
 */
class FirmTest extends \PHPUnit_Framework_TestCase {
    
    /**
    * @dataProvider getStartDateData
    */
    public function testGetStartDate($expected, $date) {
        
        $firm = new Firm();
        $firm->setStartDate($date);
        $this->AssertEquals($expected, $firm->getStartDate());
    }
    public function getStartDateData() {
        
        return array(
            
            [null, '0000-00-00'],
            ['1982-11-06', '1982-11-06'],
            [null, null]
        
            
        );
    }
    
    
    /**
    * @dataProvider getEndDateData
    */    
    public function testGetEndDate($expected, $date) {
        
        $firm = new Firm();
        $firm->setEndDate($date);
        $this->AssertEquals($expected, $firm->getEndDate());
        
    }
    public function getEndDateData() {
        
        return array(
            
            [null, '0000-00-00'],
            ['1982-11-06', '1982-11-06']
            
        );
    
    }
    
    
    public function testGetTitleFirmroles() {
        
            $firm = new Firm();
            $firmRole = new Firmrole();
            $titleFirmRole = new TitleFirmrole();
             
            $var = "Jane Taylor";
            
            $firmRole->setName($var);          
            $titleFirmRole->setFirmrole($firmRole);           
            $firm->addTitleFirmrole($titleFirmRole);
            
            $this->AssertEquals(1, count($firm->getTitleFirmroles()));
            
            $firm->removeTitleFirmrole($titleFirmRole);
            
            $this->AssertEquals(0, count($firm->getTitleFirmroles()));
        
    }
    


}
