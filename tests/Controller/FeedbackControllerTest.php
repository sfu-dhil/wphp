<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\FeedbackFixtures;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class FeedbackControllerTest extends ControllerBaseCase {
    protected function fixtures() : array {
        return [
            UserFixtures::class,
            FeedbackFixtures::class,
        ];
    }

    public function testAnonIndex() : void {

        $this->client->request('GET', '/feedback/');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserIndex() : void {
        $this->login('user.user');
        $this->client->request('GET', '/feedback/');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/feedback/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('p.count')->count());
    }

    public function testAnonShow() : void {

        $this->client->request('GET', '/feedback/1');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserShow() : void {
        $this->login('user.user');
        $this->client->request('GET', '/feedback/1');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/feedback/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1:contains("Feedback")')->count());
    }

    public function testAnonNew() : void {

        $formCrawler = $this->client->request('GET', '/feedback/new');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $formCrawler->filter('h1:contains("Feedback Creation")')->count());

        $form = $formCrawler->selectButton('Create')->form([
            'feedback[name]' => 'Bob Terwilliger',
            'feedback[email]' => 'bob@example.com',
            'feedback[content]' => 'This is a test.',
        ]);
        $this->client->submit($form);
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $responseCrawler = $this->client->followRedirect();
//        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('div.alert:contains("The new feedback was created.")')->count());
    }

    public function testUserNew() : void {
        $this->login('user.user');
        $this->client->request('GET', '/feedback/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login('user.admin');
        $this->client->request('GET', '/feedback/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }
}
