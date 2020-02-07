<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadFirm;
use AppBundle\Entity\Firm;
use AppBundle\Repository\FirmRepository;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class FirmControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadFirm::class,
        ];
    }

    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Firm')->count());
    }

    public function testUserIndex() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Firm')->count());
    }

    public function testAdminIndex() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/firm/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Add Firm')->count());
    }

    public function testAnonTypeahead() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/typeahead?q=name');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/typeahead?q=name');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/firm/typeahead?q=name');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($client->getResponse()->getContent());
        $this->assertSame(4, count($json));
    }

    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/firm/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/1/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testAdminEdit() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/firm/1/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'firm[name]' => 'Cheese.',
            'firm[streetAddress]' => '123 Cheese St.',
            'firm[city]' => '',
            'firm[gender]' => 'U',
            'firm[startDate]' => '1972',
            'firm[endDate]' => '1999',
            'firm[finalcheck]' => 1,
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/firm/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese.")')->count());
    }

    public function testAnonNew() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/new');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserNew() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/new');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/firm/new');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'firm[name]' => 'Cheese.',
            'firm[streetAddress]' => '123 Cheese St.',
            'firm[city]' => '',
            'firm[gender]' => 'U',
            'firm[startDate]' => '1972',
            'firm[endDate]' => '1999',
            'firm[finalcheck]' => 1,
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese.")')->count());
    }

    public function testAnonDelete() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/firm/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserDelete() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/firm/1/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        self::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $preCount = count($em->getRepository(Firm::class)->findAll());
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/firm/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $em->clear();
        $postCount = count($em->getRepository(Firm::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }

    public function testAnonSearch() : void {
        $repo = $this->createMock(FirmRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('firm.1')]);
        $client = $this->makeClient();
        $client->disableReboot();
        $client->getContainer()->set(FirmRepository::class, $repo);

        $formCrawler = $client->request('GET', '/firm/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'firm_search[name]' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("StreetAddress 1")')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(FirmRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('firm.1')]);
        $client = $this->makeClient(LoadUser::USER);
        $client->disableReboot();
        $client->getContainer()->set(FirmRepository::class, $repo);

        $formCrawler = $client->request('GET', '/firm/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'firm_search[name]' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("StreetAddress 1")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(FirmRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('firm.1')]);
        $client = $this->makeClient(LoadUser::ADMIN);
        $client->disableReboot();
        $client->getContainer()->set(FirmRepository::class, $repo);

        $formCrawler = $client->request('GET', '/firm/search');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'firm_search[name]' => 'adventures',
        ]);

        $responseCrawler = $client->submit($form);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("StreetAddress 1")')->count());
    }
}
