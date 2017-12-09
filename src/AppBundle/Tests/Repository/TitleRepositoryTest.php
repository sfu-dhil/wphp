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

    /**
     * @dataProvider testBuildSearchQueryData
     */
    public function testBuildSearchQuery($data, $expected) {
        $repo = $this->em->getRepository(Title::class);
        $query = $repo->buildSearchQuery($data);
        $this->assertStringEndsWith($expected, $query->getSql());
    }

    public function testBuildSearchQueryData() {
        return [
            [[], 'FROM title t0_'],
            [['title' => 'foo'], 'FROM title t0_ WHERE MATCH (t0_.title) AGAINST (? IN BOOLEAN MODE) > 0'],
            
            [['checked' => 0], 'FROM title t0_ WHERE t0_.checked = ?'],
            [['checked' => 1], 'FROM title t0_ WHERE t0_.checked = ?'],            
            [['finalcheck' => 1], 'FROM title t0_ WHERE t0_.finalcheck = ?'],
            [['finalcheck' => 1], 'FROM title t0_ WHERE t0_.finalcheck = ?'],
            
            [['pubdate' => '1900'], "FROM title t0_ WHERE YEAR(STR_TO_DATE(t0_.pubdate, '%Y')) = ?"],
            [['pubdate' => '1910-1900'], "FROM title t0_ WHERE ? <= YEAR(STR_TO_DATE(t0_.pubdate, '%Y')) AND YEAR(STR_TO_DATE(t0_.pubdate, '%Y')) <= ?"],
            [['pubdate' => '*-1900'], "FROM title t0_ WHERE ? <= YEAR(STR_TO_DATE(t0_.pubdate, '%Y')) AND YEAR(STR_TO_DATE(t0_.pubdate, '%Y')) <= ?"],
            [['pubdate' => '1900-*'], "FROM title t0_ WHERE ? <= YEAR(STR_TO_DATE(t0_.pubdate, '%Y')) AND YEAR(STR_TO_DATE(t0_.pubdate, '%Y')) <= ?"],
            
            [['location' => 'cheese'], 'FROM title t0_ INNER JOIN geonames g1_ ON t0_.location_of_printing = g1_.geonameid WHERE MATCH (g1_.alternatenames, g1_.name) AGAINST (? IN BOOLEAN MODE) > 0'],
            
            [['format' => ['a']], 'FROM title t0_ WHERE t0_.format_id IN (?)'],
            [['format' => ['a', 'b']], 'FROM title t0_ WHERE t0_.format_id IN (?)'],
            
            [['genre' => ['a']], 'FROM title t0_ WHERE t0_.genre_id IN (?)'],
            [['genre' => ['a', 'b']], 'FROM title t0_ WHERE t0_.genre_id IN (?)'],
            
            [['signed_author' => 'foo'], 'FROM title t0_ WHERE MATCH (t0_.signed_author) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['imprint' => 'foo'], 'FROM title t0_ WHERE MATCH (t0_.imprint) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['pseudonym' => 'foo'], 'FROM title t0_ WHERE MATCH (t0_.pseudonym) AGAINST (? IN BOOLEAN MODE) > 0'],

        ];
    }

}
