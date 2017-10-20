<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\Entity;
use AppBundle\Entity\Feedback;
use DateTime;


/**
 * Description of FeedbackTest
 *
 * @author dogan
 */
class FeedbackTest extends \PHPUnit_Framework_TestCase {
   
    
    
    public function testPrePersist() {
        
        $feedback = new Feedback();
        $dateTime = new DateTime();
        $feedback->prePersist();
        $this->AssertEquals($dateTime, $feedback->getCreated());
        
    }
    
 
   
}
