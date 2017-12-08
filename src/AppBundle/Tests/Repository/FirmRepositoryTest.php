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
    
    /**
     * @dataProvider buildSearchQueryData
     */
    public function testBuildSearchQuery($data, $assertion, $expected) {
        $repo = $this->em->getRepository(Firm::class);
        $query = $repo->buildSearchQuery($data);
        $this->$assertion($expected, $query->getSql());
    }
    
    public function buildSearchQueryData() {
        return [
            [[], 'assertStringEndsWith', 'FROM firm f0_'],
            
            [['address' => 'great'], 'assertStringEndsWith', 'FROM firm f0_ WHERE MATCH (f0_.street_address) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['name' => 'great'], 'assertStringEndsWith', 'FROM firm f0_ WHERE MATCH (f0_.name) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['city' => 'jordan'], 'assertStringEndsWith', 'FROM firm f0_ INNER JOIN geonames g1_ ON f0_.city = g1_.geonameid WHERE MATCH (g1_.alternatenames, g1_.name) AGAINST (? IN BOOLEAN MODE) > 0'],
            
            [['start' => '1900'], 'assertStringEndsWith', 'FROM firm f0_ WHERE YEAR(f0_.start_date) = ?'],
            [['start' => '1910-1900'], 'assertStringEndsWith', 'FROM firm f0_ WHERE ? <= YEAR(f0_.start_date) AND YEAR(f0_.start_date) <= ?'],
            [['start' => '*-1900'], 'assertStringEndsWith', 'FROM firm f0_ WHERE ? <= YEAR(f0_.start_date) AND YEAR(f0_.start_date) <= ?'],
            [['start' => '1900-*'], 'assertStringEndsWith', 'FROM firm f0_ WHERE ? <= YEAR(f0_.start_date) AND YEAR(f0_.start_date) <= ?'],
            
            [['end' => '1900'], 'assertStringEndsWith', 'FROM firm f0_ WHERE YEAR(f0_.end_date) = ?'],
            [['end' => '1910-1900'], 'assertStringEndsWith', 'FROM firm f0_ WHERE ? <= YEAR(f0_.end_date) AND YEAR(f0_.end_date) <= ?'],
            [['end' => '*-1900'], 'assertStringEndsWith', 'FROM firm f0_ WHERE ? <= YEAR(f0_.end_date) AND YEAR(f0_.end_date) <= ?'],
            [['end' => '1900-*'], 'assertStringEndsWith', 'FROM firm f0_ WHERE ? <= YEAR(f0_.end_date) AND YEAR(f0_.end_date) <= ?'],
            
            [['address' => 'great', 'name' => 'great'], 'assertStringEndsWith', 'FROM firm f0_ WHERE MATCH (f0_.name) AGAINST (? IN BOOLEAN MODE) > 0 AND MATCH (f0_.street_address) AGAINST (? IN BOOLEAN MODE) > 0'],
        ];
    }
    
}
