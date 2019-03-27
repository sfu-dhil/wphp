<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\EstcMarc;
use AppBundle\DataFixtures\ORM\LoadEstcMarc;
use AppBundle\Repository\EstcMarcRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class EstcMarcControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadEstcMarc::class
        ];
    }
    
    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/estc/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group user
     * @group index
     */
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/estc/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
   }
    
    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/estc/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/estc/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group user
     * @group show
     */
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/estc/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/estc/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAnonSearch() {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/resource/estc/search');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('estc.0.0.2')));
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $client = $this->makeClient(LoadUser::USER);
        $client->getContainer()->set(EstcMarcRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/estc/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Estc Field Data 0 100a")')->count());
    }

    public function testAdminSearch() {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('estc.0.0.2')));
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->getContainer()->set(EstcMarcRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/estc/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Estc Field Data 0 100a")')->count());
    }

    public function testAnonImprintSearch() {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/resource/estc/imprint_search');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Search')->count());
    }

    public function testUserImprintSearch() {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('imprintSearchQuery')->willReturn(array($this->getReference('estc.0.0.2')));
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $client = $this->makeClient(LoadUser::USER);
        $client->getContainer()->set(EstcMarcRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/estc/imprint_search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("ESTC Title 0")')->count());
    }

    public function testAdminImprintSearch() {
        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('imprintSearchQuery')->willReturn(array($this->getReference('estc.0.0.2')));
        $repo->method('findOneBy')->willReturn($this->getReference('estc.0.0.0'));
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->getContainer()->set(EstcMarcRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/estc/imprint_search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("ESTC Title 0")')->count());
    }

}
