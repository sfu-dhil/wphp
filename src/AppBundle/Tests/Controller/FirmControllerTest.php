<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Firm;
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
        $crawler = $client->request('GET', '/firm/4');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testUserShow() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/4');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAdminShow() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/firm/4');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
