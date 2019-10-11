<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadJackson;
use AppBundle\Repository\JacksonRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class JacksonControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return array(
            LoadUser::class,
            LoadJackson::class,
        );
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

    public function testAnonSearch() {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/resource/jackson/search');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() {
        $repo = $this->createMock(JacksonRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('jackson.1')));
        $client = $this->makeClient(LoadUser::USER);
        $client->disableReboot();
        $client->getContainer()->set(JacksonRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/jackson/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form(array(
            'q' => 'adventures',
        ));

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }

    public function testAdminSearch() {
        $repo = $this->createMock(JacksonRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('jackson.1')));
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->disableReboot();
        $client->getContainer()->set(JacksonRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/jackson/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form(array(
            'q' => 'adventures',
        ));

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }
}
