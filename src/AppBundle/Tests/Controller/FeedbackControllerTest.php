<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadFeedback;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class FeedbackControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadFeedback::class
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $client->request('GET', '/feedback/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }
    
    public function testUserIndex() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $client->request('GET', '/feedback/');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/feedback/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('p.count')->count());
    }
    
    public function testAnonShow() {
        $client = $this->makeClient();
        $client->request('GET', '/feedback/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
   
    }
    
    public function testUserShow() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $client->request('GET', '/feedback/1');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function testAdminShow() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/feedback/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Feedback")')->count());
    }
    
    public function testAnonNew() {
        
        $client = $this->makeClient();
        $formCrawler = $client->request('GET', '/feedback/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $formCrawler->filter('h1:contains("Feedback Creation")')->count());

        $form = $formCrawler->selectButton('Create')->form([
            'feedback[name]' => 'Bob Terwilliger',
            'feedback[email]' => 'bob@example.com',
            'feedback[content]' => 'This is a test.',
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $responseCrawler = $client->followRedirect();
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('div.alert:contains("The new feedback was created.")')->count());
    }
    
    public function testUserNew() {
        
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $client->request('GET', '/feedback/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        
    }
    
    public function testAdminNew() {
        
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $client->request('GET', '/feedback/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

}
