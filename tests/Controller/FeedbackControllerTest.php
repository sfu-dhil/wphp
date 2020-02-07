<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadFeedback;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class FeedbackControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadFeedback::class,
        ];
    }

    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $client->request('GET', '/feedback/');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserIndex() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $client->request('GET', '/feedback/');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminIndex() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/feedback/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('p.count')->count());
    }

    public function testAnonShow() : void {
        $client = $this->makeClient();
        $client->request('GET', '/feedback/1');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserShow() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $client->request('GET', '/feedback/1');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminShow() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/feedback/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1:contains("Feedback")')->count());
    }

    public function testAnonNew() : void {
        $client = $this->makeClient();
        $formCrawler = $client->request('GET', '/feedback/new');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $formCrawler->filter('h1:contains("Feedback Creation")')->count());

        $form = $formCrawler->selectButton('Create')->form([
            'feedback[name]' => 'Bob Terwilliger',
            'feedback[email]' => 'bob@example.com',
            'feedback[content]' => 'This is a test.',
        ]);
        $client->submit($form);
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $responseCrawler = $client->followRedirect();
//        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('div.alert:contains("The new feedback was created.")')->count());
    }

    public function testUserNew() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $client->request('GET', '/feedback/new');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $client->request('GET', '/feedback/new');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }
}
