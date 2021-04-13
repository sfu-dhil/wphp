<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\TitleFixtures;
use App\Entity\Title;
use App\Repository\TitleRepository;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class TitleControllerTest extends ControllerBaseCase
{
    protected function fixtures() : array {
        return [
            UserFixtures::class,
            TitleFixtures::class,
        ];
    }

    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/title/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Title')->count());
    }

    public function testUserIndex() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/title/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Title')->count());
    }

    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/title/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Add Title')->count());
    }

    public function testAnonTypeahead() : void {
        $crawler = $this->client->request('GET', '/title/typeahead?q=title');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $this->client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $this->client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/title/typeahead?q=title');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $this->client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/title/typeahead?q=title');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(4, $json);
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/title/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/title/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/title/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/title/1/edit');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserEdit() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/title/1/edit');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $this->login('user.admin');
        $formCrawler = $this->client->request('GET', '/title/1/edit');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'title[title]' => 'The Book of Cheese.',
            'title[editionNumber]' => 1,
            'title[signedAuthor]' => 'Testy McAuthor',
            'title[pseudonym]' => 'Author',
            'title[imprint]' => 'Cheese Publishers',
            'title[selfpublished]' => 0,
            'title[pubdate]' => '1932',
            'title[genre]' => 1,
            'title[locationOfPrinting]' => 1,
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

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/title/1'));
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('h1:contains("The Book of Cheese.")')->count());
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/title/new');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserNew() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/title/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login('user.admin');
        $formCrawler = $this->client->request('GET', '/title/new');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'title[title]' => 'The Book of Cheese.',
            'title[editionNumber]' => 1,
            'title[signedAuthor]' => 'Testy McAuthor',
            'title[pseudonym]' => 'Author',
            'title[imprint]' => 'Cheese Publishers',
            'title[selfpublished]' => 0,
            'title[pubdate]' => '1932',
            'title[genre]' => 1,
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

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('h1:contains("The Book of Cheese.")')->count());
    }

    public function testAnonDelete() : void {
        $crawler = $this->client->request('GET', '/title/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserDelete() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/title/1/delete');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        $preCount = count($this->entityManager->getRepository(Title::class)->findAll());
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/title/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->entityManager->clear();
        $postCount = count($this->entityManager->getRepository(Title::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }

    public function testAnonSearch() : void {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('title.1')]);

        $this->client->disableReboot();
        $this->client->getContainer()->set(TitleRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/title/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'title_search[title]' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('title.1')]);
        $this->login('user.user');
        $this->client->disableReboot();
        $this->client->getContainer()->set(TitleRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/title/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'title_search[title]' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('title.1')]);
        $this->login('user.admin');
        $this->client->disableReboot();
        $this->client->getContainer()->set(TitleRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/title/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'title_search[title]' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Title 1")')->count());
    }
}
