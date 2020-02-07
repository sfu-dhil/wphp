<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadEn;
use AppBundle\Repository\EnRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class EnControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadEn::class,
        ];
    }

    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/en/');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    /**
     * @group user
     * @group index
     */
    public function testUserIndex() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/en/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/en/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/resource/en/1');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    /**
     * @group user
     * @group show
     */
    public function testUserShow() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/resource/en/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/resource/en/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testAnonSearch() : void {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/resource/en/search');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(EnRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('en.1')]);
        $client = $this->makeClient(LoadUser::USER);
        $client->disableReboot();
        $client->getContainer()->set(EnRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/en/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("en-1")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(EnRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('en.1')]);
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->disableReboot();
        $client->getContainer()->set(EnRepository::class, $repo);

        $formCrawler = $client->request('GET', '/resource/en/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("en-1")')->count());
    }
}
