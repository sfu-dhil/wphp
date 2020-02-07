<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadTitle;
use AppBundle\Entity\Title;
use AppBundle\Repository\TitleRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class TitleControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadTitle::class,
        ];
    }

    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Title')->count());
    }

    public function testUserIndex() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Title')->count());
    }

    public function testAdminIndex() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Add Title')->count());
    }

    public function testAnonTypeahead() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/typeahead?q=title');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/typeahead?q=title');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/typeahead?q=title');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($client->getResponse()->getContent());
        $this->assertSame(4, count($json));
    }

    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/1/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserEdit() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/1/edit');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/title/1/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'title[title]' => 'The Book of Cheese.',
            'title[editionNumber]' => 1,
            'title[signedAuthor]' => 'Testy McAuthor',
            'title[pseudonym]' => 'Author',
            'title[imprint]' => 'Cheese Publishers',
            'title[selfpublished]' => 0,
            'title[pubdate]' => '1932',
            'title[genre]' => 1,
            'title[locationOfPrinting]' => 0,
            'title[dateOfFirstPublication]' => '1932',
            'title[sizeL]' => 1,
            'title[sizeW]' => 1,
            'title[edition]' => 'First',
            'title[volumes]' => 1,
            'title[format]' => 2,
            'title[pagination]' => '',
            'title[pricePound]' => 2,
            'title[priceShilling]' => 3,
            'title[pricePence]' => 2,
            'title[shelfmark]' => '',
            'title[checked]' => 1,
            'title[finalcheck]' => 1,
            'title[notes]' => 'It is about cheese.',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/title/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('h1:contains("The Book of Cheese.")')->count());
    }

    public function testAnonNew() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/new');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserNew() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/new');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/title/new');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'title[title]' => 'The Book of Cheese.',
            'title[editionNumber]' => 1,
            'title[signedAuthor]' => 'Testy McAuthor',
            'title[pseudonym]' => 'Author',
            'title[imprint]' => 'Cheese Publishers',
            'title[selfpublished]' => 0,
            'title[pubdate]' => '1932',
            'title[genre]' => 1,
            'title[locationOfPrinting]' => 0,
            'title[dateOfFirstPublication]' => '1932',
            'title[sizeL]' => 1,
            'title[sizeW]' => 1,
            'title[edition]' => 'First',
            'title[volumes]' => 1,
            'title[format]' => 2,
            'title[pagination]' => '',
            'title[pricePound]' => 2,
            'title[priceShilling]' => 3,
            'title[pricePence]' => 2,
            'title[shelfmark]' => '',
            'title[checked]' => 1,
            'title[finalcheck]' => 1,
            'title[notes]' => 'It is about cheese.',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('h1:contains("The Book of Cheese.")')->count());
    }

    public function testAnonDelete() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserDelete() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/1/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        self::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $preCount = count($em->getRepository(Title::class)->findAll());
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $em->clear();
        $postCount = count($em->getRepository(Title::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }

    public function testAnonSearch() : void {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('title.1')]);
        $client = $this->makeClient();
        $client->disableReboot();
        $client->getContainer()->set(TitleRepository::class, $repo);

        $formCrawler = $client->request('GET', '/title/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'title_search[title]' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('title.1')]);
        $client = $this->makeClient(LoadUser::USER);
        $client->disableReboot();
        $client->getContainer()->set(TitleRepository::class, $repo);

        $formCrawler = $client->request('GET', '/title/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'title_search[title]' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('title.1')]);
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->disableReboot();
        $client->getContainer()->set(TitleRepository::class, $repo);

        $formCrawler = $client->request('GET', '/title/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'title_search[title]' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }
}
