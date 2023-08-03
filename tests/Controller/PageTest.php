<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\TestCase\ControllerTestCase;
use Symfony\Component\HttpFoundation\Response;

class PageTest extends ControllerTestCase {
    // Change this to HTTP_OK when the site is public.
    private const ANON_RESPONSE_CODE = Response::HTTP_OK;

    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/blog/page/');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testUserIndex() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/page/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testAdminIndex() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/page/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('New')->count());
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/blog/page/1');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
    }

    public function testUserShow() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/page/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
    }

    public function testAdminShow() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/page/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
    }

    public function testAnonSearch() : void {
        $crawler = $this->client->request('GET', '/blog/page/search');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        if (self::ANON_RESPONSE_CODE === Response::HTTP_FOUND) {
            // If authentication is required stop here.
            return;
        }

        $form = $crawler->selectButton('Search')->form([
            'q' => 'page',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testUserSearch() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/page/search');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Search')->form([
            'q' => 'page',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testAdminSearch() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/page/search');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Search')->form([
            'q' => 'page',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/blog/page/1/edit');
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
    }

    public function testUserEdit() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/page/1/edit');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/page/1/edit');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'page[inMenu]' => 1,
            'page[public]' => 1,
            'page[homepage]' => 1,
            'page[includeComments]' => 1,
            'page[title]' => 'Updated Title',
            'page[excerpt]' => '<p>Updated Text</p>',
            'page[content]' => '<p>Updated Text</p>',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/page/1', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/blog/page/new');
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
    }

    public function testUserNew() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/page/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/page/new');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'page[inMenu]' => 1,
            'page[public]' => 1,
            'page[homepage]' => 1,
            'page[includeComments]' => 1,
            'page[title]' => 'Updated Title',
            'page[excerpt]' => '<p>Updated Text</p>',
            'page[content]' => '<p>Updated Text</p>',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/page/6', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAnonSort() : void {
        $crawler = $this->client->request('GET', '/blog/page/sort');
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
    }

    public function testUserSort() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/page/sort');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminSort() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/page/sort');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'order' => '2, 1, 3, 5, 4',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/page/', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }
}
