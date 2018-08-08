<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Jackson;
use AppBundle\DataFixtures\ORM\LoadJackson;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class JacksonControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadJackson::class
        ];
    }
    
    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/jackson/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }
    
    /**
     * @group user
     * @group index
     */
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/jackson/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }
    
    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/jackson/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('New')->count());
    }
    
    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/jackson/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }
    
    /**
     * @group user
     * @group show
     */
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/jackson/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }
    
    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/jackson/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('Edit')->count());
        $this->assertEquals(1, $crawler->selectLink('Delete')->count());
    }

    /**
     * @group anon
     * @group typeahead
     */
    public function testAnonTypeahead() {
        $client = $this->makeClient();
        $client->request('GET', '/resource/jackson/typeahead?q=STUFF');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('content-type'));
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );        
        $json = json_decode($response->getContent());
        $this->assertEquals(4, count($json));
    }

    /**
     * @group user
     * @group typeahead
     */
    public function testUserTypeahead() {
        $client = $this->makeClient(LoadUser::USER);
        $client->request('GET', '/resource/jackson/typeahead?q=STUFF');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('content-type'));
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );        
        $json = json_decode($response->getContent());
        $this->assertEquals(4, count($json));
    }

    /**
     * @group admin
     * @group typeahead
     */
    public function testAdminTypeahead() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->request('GET', '/resource/jackson/typeahead?q=STUFF');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('content-type'));
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );        
        $json = json_decode($response->getContent());
        $this->assertEquals(4, count($json));
    }

}
