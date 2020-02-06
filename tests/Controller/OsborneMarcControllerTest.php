<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadOsborneMarc;
use AppBundle\Repository\OsborneMarcRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class OsborneMarcControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return array(
            LoadUser::class,
            LoadOsborneMarc::class,
        );
    }

    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/osborne/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group index
     */
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/osborne/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/osborne/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/osborne/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group show
     */
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/osborne/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/osborne/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAnonSearch() {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/resource/estc/search');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() {
        $repo = $this->createMock(OsborneMarcRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('osborne.0.0.2')));
        $repo->method('findOneBy')->willReturn($this->getReference('osborne.0.0.0'));
        $client = $this->makeClient(LoadUser::USER);
        $client->getContainer()->set(OsborneMarcRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/osborne/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form(array(
            'q' => 'adventures',
        ));

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Osborne Field Data")')->count());
    }

    public function testAdminSearch() {
        $repo = $this->createMock(OsborneMarcRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('osborne.0.0.2')));
        $repo->method('findOneBy')->willReturn($this->getReference('osborne.0.0.0'));
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->getContainer()->set(OsborneMarcRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/osborne/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form(array(
            'q' => 'adventures',
        ));

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Osborne Field Data")')->count());
    }
}
