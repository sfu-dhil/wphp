<?php

namespace AppBundle\Tests\Repository;

use AppBundle\DataFixtures\ORM\LoadPerson;
use AppBundle\Entity\Person;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class PersonRepositoryTest extends BaseTestCase {
    
    protected function getFixtures() {
        return [
            LoadPerson::class
        ];
    }
    
    public function testNext() {
        $repo = $this->em->getRepository(Person::class);
        $person = $this->getReference('person.1');
        $next = $repo->next($person);
        $this->assertNotNull($next);
        $this->assertEquals($next->getId(), $this->getReference('person.2')->getId());
    }
    
    public function testNullNext() {
        $repo = $this->em->getRepository(Person::class);
        $person = $this->getReference('person.3');
        $next = $repo->next($person);
        $this->assertNull($next);
    }
    
    public function testPrevious() {
        $repo = $this->em->getRepository(Person::class);
        $person = $this->getReference('person.2');
        $previous = $repo->previous($person);
        $this->assertNotNull($previous);
        $this->assertEquals($previous->getId(), $this->getReference('person.1')->getId());
    }
    
    public function testNullPrevious() {
        $repo = $this->em->getRepository(Person::class);
        $person = $this->getReference('person.0');
        $previous = $repo->previous($person);
        $this->assertNull($previous);
    }
    
    /**
     * @dataProvider testBuildSearchQueryData
     */
    public function testBuildSearchQuery($data, $expected) {
        $repo = $this->em->getRepository(Person::class);
        $query = $repo->buildSearchQuery($data);
        $this->assertStringEndsWith($expected, $query->getSql());
    }
    
    public function testBuildSearchQueryData() {
        return [
            [[], 'FROM person p0_'],
            [['name' => 'mary'], 'FROM person p0_ WHERE MATCH (p0_.last_name, p0_.first_name, p0_.title_name) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['gender' => ['F']], 'FROM person p0_ WHERE p0_.gender IN (?)'],
            [['gender' => ['F', 'U']], 'FROM person p0_ WHERE p0_.gender IN (?)'],
            
            [['dob' => '1900'], 'FROM person p0_ WHERE YEAR(p0_.dob) = ?'],
            [['dob' => '1910-1900'], 'FROM person p0_ WHERE ? <= YEAR(p0_.dob) AND YEAR(p0_.dob) <= ?'],
            [['dob' => '*-1900'], 'FROM person p0_ WHERE ? <= YEAR(p0_.dob) AND YEAR(p0_.dob) <= ?'],
            [['dob' => '1900-*'], 'FROM person p0_ WHERE ? <= YEAR(p0_.dob) AND YEAR(p0_.dob) <= ?'],
            
            [['dod' => '1900'], 'FROM person p0_ WHERE YEAR(p0_.dod) = ?'],
            [['dod' => '1910-1900'], 'FROM person p0_ WHERE ? <= YEAR(p0_.dod) AND YEAR(p0_.dod) <= ?'],
            [['dod' => '*-1900'], 'FROM person p0_ WHERE ? <= YEAR(p0_.dod) AND YEAR(p0_.dod) <= ?'],
            [['dod' => '1900-*'], 'FROM person p0_ WHERE ? <= YEAR(p0_.dod) AND YEAR(p0_.dod) <= ?'],
            
            [['birthplace' => 'kamloops'], 'FROM person p0_ INNER JOIN geonames g1_ ON p0_.city_id_of_birth = g1_.geonameid WHERE MATCH (g1_.alternatenames, g1_.name) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['deathplace' => 'kamloops'], 'FROM person p0_ INNER JOIN geonames g1_ ON p0_.city_id_of_death = g1_.geonameid WHERE MATCH (g1_.alternatenames, g1_.name) AGAINST (? IN BOOLEAN MODE) > 0'],
            
            [['title_filter' => ['title' => 'rocks']], 'FROM person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE MATCH (t2_.title) AGAINST (? IN BOOLEAN MODE) > 0'],
            [['title_filter' => ['person_role' => 'engraver']], 'FROM person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE t1_.role_id IN (?)'],

            [['title_filter' => ['pubdate' => '1900']], "FROM person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE YEAR(STR_TO_DATE(t2_.pubdate, '%Y')) = ?"],
            [['title_filter' => ['pubdate' => '1910-1900']], "person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE ? <= YEAR(STR_TO_DATE(t2_.pubdate, '%Y')) AND YEAR(STR_TO_DATE(t2_.pubdate, '%Y')) <= ?"],
            [['title_filter' => ['pubdate' => '*-1900']], "person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE ? <= YEAR(STR_TO_DATE(t2_.pubdate, '%Y')) AND YEAR(STR_TO_DATE(t2_.pubdate, '%Y')) <= ?"],
            [['title_filter' => ['pubdate' => '1900-*']], "person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE ? <= YEAR(STR_TO_DATE(t2_.pubdate, '%Y')) AND YEAR(STR_TO_DATE(t2_.pubdate, '%Y')) <= ?"],
            
            [['title_filter' => ['genre' => ['fiction']]], 'person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE t2_.genre_id IN (?)'],
            [['title_filter' => ['genre' => ['fiction', 'cheese']]], 'person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id WHERE t2_.genre_id IN (?)'],
            
            [['title_filter' => ['location' => 'bermuda']], 'FROM person p0_ INNER JOIN title_role t1_ ON p0_.id = t1_.person_id INNER JOIN title t2_ ON t1_.title_id = t2_.id INNER JOIN geonames g3_ ON t2_.location_of_printing = g3_.geonameid WHERE MATCH (g3_.alternatenames, g3_.name) AGAINST (? IN BOOLEAN MODE) > 0'],
            
            [['orderby' => 'firstname'], 'FROM person p0_ ORDER BY p0_.first_name ASC'],
            [['orderby' => 'dob'], 'FROM person p0_ ORDER BY DATE(p0_.dob) ASC'],
            [['orderby' => 'dod'], 'FROM person p0_ ORDER BY DATE(p0_.dod) ASC'],
            [['orderby' => 'lastname'], 'FROM person p0_ ORDER BY p0_.last_name ASC'],
            [['orderby' => 'cheese'], 'FROM person p0_ ORDER BY p0_.last_name ASC'],
            
            [['orderby' => 'firstname', 'orderdir' => 'ASC'], 'FROM person p0_ ORDER BY p0_.first_name ASC'],
            [['orderby' => 'dob', 'orderdir' => 'ASC'], 'FROM person p0_ ORDER BY DATE(p0_.dob) ASC'],
            [['orderby' => 'dod', 'orderdir' => 'ASC'], 'FROM person p0_ ORDER BY DATE(p0_.dod) ASC'],
            [['orderby' => 'lastname', 'orderdir' => 'ASC'], 'FROM person p0_ ORDER BY p0_.last_name ASC'],
            [['orderby' => 'cheese', 'orderdir' => 'ASC'], 'FROM person p0_ ORDER BY p0_.last_name ASC'],
            
            [['orderby' => 'firstname', 'orderdir' => 'DESC'], 'FROM person p0_ ORDER BY p0_.first_name DESC'],
            [['orderby' => 'dob', 'orderdir' => 'DESC'], 'FROM person p0_ ORDER BY DATE(p0_.dob) DESC'],
            [['orderby' => 'dod', 'orderdir' => 'DESC'], 'FROM person p0_ ORDER BY DATE(p0_.dod) DESC'],
            [['orderby' => 'lastname', 'orderdir' => 'DESC'], 'FROM person p0_ ORDER BY p0_.last_name DESC'],
            [['orderby' => 'cheese', 'orderdir' => 'DESC'], 'FROM person p0_ ORDER BY p0_.last_name DESC'],
        ];
    }
    
}
