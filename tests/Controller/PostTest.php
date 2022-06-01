<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\TestCase\ControllerTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostTest extends ControllerTestCase {
    // Change this to HTTP_OK when the site is public.
    private const ANON_RESPONSE_CODE = Response::HTTP_OK;

    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/blog/post/');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testUserIndex() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('New')->count());
    }

    public function testAdminIndex() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/');
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('New')->count());
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/blog/post/1');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
    }

    public function testUserShow() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
    }

    public function testAdminShow() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/1');
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
    }

    public function testAnonSearch() : void {
        $crawler = $this->client->request('GET', '/blog/post/search');
        $this->assertResponseStatusCodeSame(self::ANON_RESPONSE_CODE);
        if (self::ANON_RESPONSE_CODE === Response::HTTP_FOUND) {
            // If authentication is required stop here.
            return;
        }

        $form = $crawler->selectButton('Search')->form([
            'q' => 'post',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testUserSearch() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/search');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Search')->form([
            'q' => 'post',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testAdminSearch() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/blog/post/search');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Search')->form([
            'q' => 'post',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertResponseIsSuccessful();
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/blog/post/1/edit');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testUserEdit() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/1/edit');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/post/1/edit');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'post[includeComments]' => 1,
            'post[title]' => 'Updated Title',
            'post[excerpt]' => '<p>Updated Text</p>',
            'post[content]' => '<p>Updated Text</p>',
        ]);
        $this->overrideField($form, 'post[category]', 2);
        $this->overrideField($form, 'post[status]', 2);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/1', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/blog/post/new');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testAnonNewPopup() : void {
        $crawler = $this->client->request('GET', '/blog/post/new_popup');
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testUserNew() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testUserNewPopup() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/blog/post/new_popup');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/post/new');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'post[includeComments]' => 1,
            'post[title]' => 'Updated Title',
            'post[excerpt]' => '<p>Updated Text</p>',
            'post[content]' => '<p>Updated Text</p>',
        ]);
        $this->overrideField($form, 'post[category]', 2);
        $this->overrideField($form, 'post[status]', 2);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/6', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAdminNewPopup() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/blog/post/new');
        $this->assertResponseIsSuccessful();

        $form = $formCrawler->selectButton('Save')->form([
            'post[includeComments]' => 1,
            'post[title]' => 'Updated Title',
            'post[excerpt]' => '<p>Updated Text</p>',
            'post[content]' => '<p>Updated Text</p>',
        ]);
        $this->overrideField($form, 'post[category]', 2);
        $this->overrideField($form, 'post[status]', 2);

        $this->client->submit($form);
        $this->assertResponseRedirects('/blog/post/7', Response::HTTP_FOUND);
        $responseCrawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }
}
