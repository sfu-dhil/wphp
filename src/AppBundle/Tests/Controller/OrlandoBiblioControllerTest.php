<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\OrlandoBiblio;
use AppBundle\DataFixtures\ORM\LoadOrlandoBiblio;
use AppBundle\Repository\OrlandoBiblioRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class OrlandoBiblioControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadOrlandoBiblio::class
        ];
    }
    
    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/orlando_biblio/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group user
     * @group index
     */
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/orlando_biblio/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/orlando_biblio/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/orlando_biblio/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group user
     * @group show
     */
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/orlando_biblio/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/orlando_biblio/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    public function testAnonSearch() {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/resource/orlando_biblio/search');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() {
        $repo = $this->createMock(OrlandoBiblioRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('orlando.1')));
        $client = $this->makeClient(LoadUser::USER);
        $client->disableReboot();
        $client->getContainer()->set(OrlandoBiblioRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/orlando_biblio/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("1880")')->count());
    }

    public function testAdminSearch() {
        $repo = $this->createMock(OrlandoBiblioRepository::class);
        $repo->method('searchQuery')->willReturn(array($this->getReference('orlando.1')));
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->disableReboot();
        $client->getContainer()->set(OrlandoBiblioRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/orlando_biblio/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("1880")')->count());
    }

}
