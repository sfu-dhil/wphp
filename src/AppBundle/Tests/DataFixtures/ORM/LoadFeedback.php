<?php

namespace AppBundle\Tests\DataFixtures\ORM;

use AppBundle\Entity\Feedback;
use AppBundle\Tests\Util\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;


class LoadFeedback extends AbstractDataFixture {
    
    protected function doLoad(ObjectManager $manager) {
        $feedback = new Feedback();
        $feedback->setContent("I am a feedback.");
        $feedback->setEmail('feedback@example.com');
        $feedback->setName("Bobby Feedback");
        
        $manager->persist($feedback);
        $manager->flush();
    }

    protected function getEnvironments() {
        return ['test'];
    }

}
