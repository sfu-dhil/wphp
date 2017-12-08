<?php

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\Firm;
use AppBundle\Entity\Title;
use AppBundle\Tests\DataFixtures\ORM\LoadTitle;
use AppBundle\Tests\Util\BaseTestCase;

class TitleRepositoryTest extends BaseTestCase {
    
    protected function getFixtures() {
        return [
            LoadTitle::class
        ];
    }
    
    public function testNext() {
        $repo = $this->em->getRepository(Title::class);
        $firm = $this->getReference('title.1');
        $next = $repo->next($firm);
        $this->assertNotNull($next);
        $this->assertEquals($next->getId(), $this->getReference('title.2')->getId());
    }
    
    public function testNextNull() {
        $repo = $this->em->getRepository(Title::class);
        $firm = $this->getReference('title.2');
        $next = $repo->next($firm);
        $this->assertNull($next);
    }
    
    public function testPrevious() {
        $repo = $this->em->getRepository(Title::class);
        $firm = $this->getReference('title.2');
        $previous = $repo->previous($firm);
        $this->assertNotNull($previous);
        $this->assertEquals($previous->getId(), $this->getReference('title.1')->getId());
    }
    
    public function testPreviousNull() {
        $repo = $this->em->getRepository(Title::class);
        $firm = $this->getReference('title.1');
        $previous = $repo->previous($firm);
        $this->assertNull($previous);
    }
}
