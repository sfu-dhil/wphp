<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\DataFixtures\ORM\LoadFirm;
use AppBundle\Tests\Util\BaseTestCase;
use Nines\UserBundle\Tests\DataFixtures\ORM\LoadUsers;

class FirmControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUsers::class,
            LoadFirm::class
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Firm List")')->count());
       
    }
    
    public function testUserIndex() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Firm List")')->count());
      
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/firm/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Firm List")')->count());
     
    }
    
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testUserShow() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAdminShow() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/firm/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testAnonJump() {
        
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Jump')->form(array('q' => '4'));
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect('/firm/4'));
    }
    
    public function testUserJump() {
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/firm/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Jump')->form(array('q' => '4'));
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect('/firm/4'));
        
        
    }
    
    public function testAdminJump() {
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/firm/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Jump')->form(array('q' => '4'));
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect('/firm/4'));
        
        
    }
    
    public function testAnonQuickSearch() {
        
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form);
        
        $this->assertEquals(1, $crawler->filter('h1:contains("Firm Search")')->count());
    }
    
    
    public function testUserQuickSearch() {
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/firm/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form);
        
        $this->assertEquals(1, $crawler->filter('h1:contains("Firm Search")')->count());
    }
    
    public function testAdminQuickSearch() {
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/firm/');
        
        $link = $crawler->selectLink('Search')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form);
        
        $this->assertEquals(1, $crawler->filter('h1:contains("Firm Search")')->count());
    }
    
    
    public function testAnonSearch() {
        $this->markTestSkipped('Cannot test this page with SQLite.');
        
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/search');
        
        $form = $crawler->selectButton('Search')->form(array('firm_search[name]' => 'Great Firm'));
        $crawler = $client->submit($form);
        
        
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Great Firm")')->count());
        
    }
    
    public function testUserSearch() {
        $this->markTestSkipped('Cannot test this page with SQLite.');
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/firm/search');
        
        $form = $crawler->selectButton('Search')->form(array('firm_search[name]' => 'Great Firm'));
        $crawler = $client->submit($form);
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Great Firm")')->count());
        
    }
    
    public function testAdminSearch() {
        $this->markTestSkipped('Cannot test this page with SQLite.');
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/firm/search');
        
        $form = $crawler->selectButton('Search')->form(array('firm_search[name]' => 'Great Firm'));
        $crawler = $client->submit($form);
        
        
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Great Firm")')->count());
    }  
    
    public function testJson()
    {
    
        $client = $this->makeClient();
        $client->request('GET', '/firm/1.json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    
        /*
        $response = $client->getResponse();
        $content = $response->getContent();
        $decodedContent = json_decode($content, true);
        */
        
    }

    
}
