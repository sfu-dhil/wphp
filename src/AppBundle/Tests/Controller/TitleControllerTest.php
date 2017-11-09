<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Title;
use AppBundle\Tests\DataFixtures\ORM\LoadTitle;
use AppBundle\Tests\Util\BaseTestCase;
use Nines\UserBundle\Tests\DataFixtures\ORM\LoadUsers;

class TitleControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUsers::class,
            LoadTitle::class
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Title List")')->count());
    }
    
    public function testUserIndex() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Title List")')->count());
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Title List")')->count());
    }
    
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    
    }
    
    public function testUserShow() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
       
    }
    
    public function testAdminShow() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    
    
    public function testAnonSearch() {
        
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/search');
        
        $form = $crawler->selectButton('Search')->form(array('firm_search[name]' => 'Demolition Man'));
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('tr')->count());
        
    }
    
    public function testUserSearch() {
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        
        $crawler = $client->request('GET', '/title/search');
        
        $form = $crawler->selectButton('Search')->form(array('firm_search[name]' => 'Demolition Man'));
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('tr')->count());
        
    }
    
    public function testAdminSearch() {
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        
        $crawler = $client->request('GET', '/title/search');
        
        $form = $crawler->selectButton('Search')->form(array('firm_search[name]' => 'Demolition Man'));
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter('tr')->count());
    }  

}
