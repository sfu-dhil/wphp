<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\JacksonFixtures;
use App\Repository\JacksonRepository;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class JacksonControllerTest extends ControllerBaseCase
{
    protected function fixtures() : array {
        return [
            UserFixtures::class,
            JacksonFixtures::class,
        ];
    }

    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/resource/jackson/');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group index
     */
    public function testUserIndex() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/resource/jackson/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/resource/jackson/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/resource/jackson/1');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group user
     * @group show
     */
    public function testUserShow() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/resource/jackson/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/resource/jackson/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonSearch() : void {
        $crawler = $this->client->request('GET', '/resource/jackson/search');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Search')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(JacksonRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('jackson.1')]);
        $this->login('user.user');
        $this->client->disableReboot();
        $this->client->getContainer()->set(JacksonRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/resource/jackson/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(JacksonRepository::class);
        $repo->method('searchQuery')->willReturn([$this->getReference('jackson.1')]);
        $this->login('user.admin');
        $this->client->disableReboot();
        $this->client->getContainer()->set(JacksonRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/resource/jackson/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'q' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }
}
