<?php

namespace AppBundle\Tests\Repository;

use AppBundle\DataFixtures\ORM\LoadTitle;
use AppBundle\Entity\Title;
use Doctrine\ORM\Query;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

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
        $firm = $this->getReference('title.3');
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
        $firm = $this->getReference('title.0');
        $previous = $repo->previous($firm);
        $this->assertNull($previous);
    }

    /**
     * @dataProvider testBuildSearchQueryData
     */
    public function testBuildSearchQuery($data, $expected) {
        $repo = $this->em->getRepository(Title::class);
        $query = $repo->buildSearchQuery($data);
        $this->assertInstanceOf(Query::class, $query);
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
            
            [['orderby' => ''], 'FROM title t0_'],
            [['orderby' => 'title'], 'FROM title t0_ ORDER BY t0_.title ASC'],
            [['orderby' => 'title', 'orderdir' => 'asc'], 'FROM title t0_ ORDER BY t0_.title ASC'],
            [['orderby' => 'title', 'orderdir' => 'desc'], 'FROM title t0_ ORDER BY t0_.title DESC'],
            
            [['orderby' => 'pubdate'], 'FROM title t0_ ORDER BY t0_.pubdate ASC'],
            [['orderby' => 'pubdate', 'orderdir' => 'asc'], 'FROM title t0_ ORDER BY t0_.pubdate ASC'],
            [['orderby' => 'pubdate', 'orderdir' => 'desc'], 'FROM title t0_ ORDER BY t0_.pubdate DESC'],
            
            [['orderby' => 'cheese', 'orderdir' => 'colour'], 'FROM title t0_ ORDER BY t0_.title ASC'],
            
            [['person_filter' => []], 'FROM title t0_'],
            [['person_filter' => ['name' => 'foo']], 'FROM title t0_ INNER JOIN title_role t1_ ON t0_.id = t1_.title_id INNER JOIN person p2_ ON t1_.person_id = p2_.id WHERE MATCH (p2_.last_name, p2_.first_name, p2_.title_name) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['person_filter' => ['gender' => ['M']]], 'FROM title t0_ INNER JOIN title_role t1_ ON t0_.id = t1_.title_id INNER JOIN person p2_ ON t1_.person_id = p2_.id WHERE p2_.gender IN (?)'],
            [['person_filter' => ['gender' => ['M', 'F']]], 'FROM title t0_ INNER JOIN title_role t1_ ON t0_.id = t1_.title_id INNER JOIN person p2_ ON t1_.person_id = p2_.id WHERE p2_.gender IN (?)'],
            [['person_filter' => ['person_role' => ['author']]], 'FROM title t0_ INNER JOIN title_role t1_ ON t0_.id = t1_.title_id INNER JOIN person p2_ ON t1_.person_id = p2_.id WHERE t1_.role_id IN (?)'],
            [['person_filter' => ['person_role' => ['author', 'cheese maker']]], 'FROM title t0_ INNER JOIN title_role t1_ ON t0_.id = t1_.title_id INNER JOIN person p2_ ON t1_.person_id = p2_.id WHERE t1_.role_id IN (?)'],
            
            [['firm_filter' => []], 'FROM title t0_'],
            [['firm_filter' => ['firm_name' => 'cheeseries']], 'FROM title t0_ INNER JOIN title_firmrole t1_ ON t0_.id = t1_.title_id INNER JOIN firm f2_ ON t1_.firm_id = f2_.id WHERE MATCH (f2_.name) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['firm_filter' => ['firm_role' => ['cheeseries']]], 'FROM title t0_ INNER JOIN title_firmrole t1_ ON t0_.id = t1_.title_id INNER JOIN firm f2_ ON t1_.firm_id = f2_.id WHERE t1_.firmrole_id IN (?)'],
            [['firm_filter' => ['firm_role' => ['bakeries', 'cheeseries']]], 'FROM title t0_ INNER JOIN title_firmrole t1_ ON t0_.id = t1_.title_id INNER JOIN firm f2_ ON t1_.firm_id = f2_.id WHERE t1_.firmrole_id IN (?)'],
            [['firm_filter' => ['firm_address' => '10 downing']], 'FROM title t0_ INNER JOIN title_firmrole t1_ ON t0_.id = t1_.title_id INNER JOIN firm f2_ ON t1_.firm_id = f2_.id WHERE MATCH (f2_.street_address) AGAINST (? IN BOOLEAN MODE) > 0'],
            
        ];
    }

}
