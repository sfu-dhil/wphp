<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Format;
use AppBundle\Tests\DataFixtures\ORM\LoadFormat;
use AppBundle\Tests\Util\BaseTestCase;
use Nines\UserBundle\Tests\DataFixtures\ORM\LoadUsers;

class FormatControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUsers::class,
            LoadFormat::class
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
       
    }
    
    public function testUserIndex() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/format/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testUserShow() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAdminShow() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/format/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }

}
