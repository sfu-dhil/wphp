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
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group user
     * @group index
     */
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/jackson/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/jackson/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/jackson/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group user
     * @group show
     */
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/jackson/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/jackson/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
