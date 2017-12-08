<?php

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\Firm;
use AppBundle\Tests\DataFixtures\ORM\LoadFirm;
use AppBundle\Tests\Util\BaseTestCase;

class FirmRepositoryTest extends BaseTestCase {
    
    protected function getFixtures() {
        return [
            LoadFirm::class
        ];
    }
    
    public function testNext() {
        $repo = $this->em->getRepository(Firm::class);
        $firm = $this->getReference('firm.1');
        $next = $repo->next($firm);
        $this->assertNotNull($next);
        $this->assertEquals($next->getId(), $this->getReference('firm.2')->getId());
    }
    
    public function testNextNull() {
        $repo = $this->em->getRepository(Firm::class);
        $firm = $this->getReference('firm.2');
        $next = $repo->next($firm);
        $this->assertNull($next);
    }
    
    public function testPrevious() {
        $repo = $this->em->getRepository(Firm::class);
        $firm = $this->getReference('firm.2');
        $previous = $repo->previous($firm);
        $this->assertNotNull($previous);
        $this->assertEquals($previous->getId(), $this->getReference('firm.1')->getId());
    }
    
    public function testPreviousNull() {
        $repo = $this->em->getRepository(Firm::class);
        $firm = $this->getReference('firm.1');
        $previous = $repo->previous($firm);
        $this->assertNull($previous);
    }
    
    public function testRandom() {
        $this->markTestSkipped('Cannot test method with SQLite.');        
        $repo = $this->em->getRepository(Firm::class);
        $firm = $repo->random(1);
        $this->assertNotNull($firm);
        $this->assertEquals(1, $firm->count());
    }
}
