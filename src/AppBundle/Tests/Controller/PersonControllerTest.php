<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Person;
use AppBundle\Tests\DataFixtures\ORM\LoadPerson;
use AppBundle\Tests\Util\BaseTestCase;
use Nines\UserBundle\Tests\DataFixtures\ORM\LoadUsers;

class PersonControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUsers::class,
            LoadPerson::class
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/person/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Person List")')->count());
    }
    
    public function testUserIndex() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/person/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Person List")')->count());
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/person/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Person List")')->count());
    }
    
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/person/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
   
    }
    
    public function testUserShow() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/person/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAdminShow() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/person/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAnonJump() {
        
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/person/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Jump')->form(array('q' => '4'));
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect('/person/4'));
    }
    
    public function testUserJump() {
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/person/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Jump')->form(array('q' => '4'));
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect('/person/4'));
        
        
    }
    
    public function testAdminJump() {
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/person/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Jump')->form(array('q' => '4'));
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect('/person/4'));
        
        
    }
    
    public function testAnonQuickSearch() {
        
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/person/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form);
        
        $this->assertEquals(1, $crawler->filter('h1:contains("Person Search")')->count());
    }
    
    
    public function testUserQuickSearch() {
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/person/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form);
        
        $this->assertEquals(1, $crawler->filter('h1:contains("Person Search")')->count());
    }
    
    public function testAdminQuickSearch() {
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/person/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form);
        
        $this->assertEquals(1, $crawler->filter('h1:contains("Person Search")')->count());
    }
    
    public function testAnonSearch() {
        $this->markTestSkipped('Cannot test this page with SQLite.');
        
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/person/search');
        
        $form = $crawler->selectButton('Search')->form(array('person_search[name]' => 'Bobby Rock'));
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('tr')->count());
        
    }
    
    public function testUserSearch() {
        $this->markTestSkipped('Cannot test this page with SQLite.');
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/person/search');
        
        $form = $crawler->selectButton('Search')->form(array('person_search[name]' => 'Bobby Rock'));
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('tr')->count());
        
    }
    
    public function testAdminSearch() {
        $this->markTestSkipped('Cannot test this page with SQLite.');
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/person/search');
        
        $form = $crawler->selectButton('Search')->form(array('person_search[name]' => 'Bobby Rock'));
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('tr')->count());
    }  
    
    public function testAnonExport() {
        
        $client = $this->makeClient();
        
        $crawler = $client->request('GET', '/person/1/export?format=mla');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=apa');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=chicago');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=bibtex');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testUserExport() {
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/person/1/export?format=mla');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=apa');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=chicago');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=bibtex');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testAdminExport() {
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/person/1/export?format=mla');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=apa');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=chicago');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('GET', '/person/1/export?format=bibtex');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testJson()
    {
    
        $client = $this->makeClient();
        $client->request('GET', '/person/1.json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    
        /*
        $response = $client->getResponse();
        $content = $response->getContent();
        $decodedContent = json_decode($content, true);
        */
        
    }

}
