<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feedback;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFeedback form.
 */
class LoadFeedback extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Feedback();
            $fixture->setName('Name ' . $i);
            $fixture->setEmail('Email ' . $i);
            $fixture->setContent('Content ' . $i);
            
            $em->persist($fixture);
            $this->setReference('feedback.' . $i, $fixture);
        }
        
        $em->flush();
        
    }
        
}
