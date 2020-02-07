<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadFormat;
use AppBundle\Entity\Format;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class FormatControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadFormat::class,
        ];
    }

    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Format')->count());
    }

    public function testUserIndex() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Format')->count());
    }

    public function testAdminIndex() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/format/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Add Format')->count());
    }

    public function testAnonTypeahead() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/typeahead?q=name');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/typeahead?q=name');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/format/typeahead?q=name');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($client->getResponse()->getContent());
        $this->assertSame(4, count($json));
    }

    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/format/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/1/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserEdit() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/1/edit');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/format/1/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'format[name]' => 'Cheese.',
            'format[abbreviation]' => 'Cheese',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/format/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese.")')->count());
    }

    public function testAnonNew() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/new');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserNew() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/new');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/format/new');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'format[name]' => 'Cheese.',
            'format[abbreviation]' => 'Cheese',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese.")')->count());
    }

    public function testAnonDelete() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/format/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserDelete() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/format/1/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        self::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $preCount = count($em->getRepository(Format::class)->findAll());
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/format/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $em->clear();
        $postCount = count($em->getRepository(Format::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }
}
