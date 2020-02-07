<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadOrlandoBiblio;
use AppBundle\Repository\OrlandoBiblioRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class OrlandoBiblioControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadOrlandoBiblio::class,
        ];
    }

    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/orlando_biblio/');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group index
     */
    public function testUserIndex() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/orlando_biblio/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/orlando_biblio/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/orlando_biblio/1');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group show
     */
    public function testUserShow() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/orlando_biblio/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/orlando_biblio/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testAnonSearch() : void {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/resource/orlando_biblio/search');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(OrlandoBiblioRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('orlando.1')]);
        $client = $this->makeClient(LoadUser::USER);
        $client->disableReboot();
        $client->getContainer()->set(OrlandoBiblioRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/orlando_biblio/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("1880")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(OrlandoBiblioRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('orlando.1')]);
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->disableReboot();
        $client->getContainer()->set(OrlandoBiblioRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/orlando_biblio/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("1880")')->count());
    }
}
