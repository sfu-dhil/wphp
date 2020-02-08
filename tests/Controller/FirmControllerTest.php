<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\FirmFixtures;
use App\Entity\Firm;
use App\Repository\FirmRepository;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class FirmControllerTest extends ControllerBaseCase {

    protected function fixtures() : array {
        return [
            UserFixtures::class,
            FirmFixtures::class,
        ];
    }

    public function testAnonIndex() : void {

        $crawler = $this->client->request('GET', '/firm/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Firm')->count());
    }

    public function testUserIndex() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/firm/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Firm')->count());
    }

    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/firm/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Add Firm')->count());
    }

    public function testAnonTypeahead() : void {

        $crawler = $this->client->request('GET', '/firm/typeahead?q=name');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $this->client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $this->client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/firm/typeahead?q=name');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $this->client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/firm/typeahead?q=name');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($this->client->getResponse()->getContent());
        $this->assertSame(4, count($json));
    }

    public function testAnonShow() : void {

        $crawler = $this->client->request('GET', '/firm/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/firm/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/firm/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {

        $crawler = $this->client->request('GET', '/firm/1/edit');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testAdminEdit() : void {
        $this->login('user.admin');
        $formCrawler = $this->client->request('GET', '/firm/1/edit');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'firm[name]'          => 'Cheese.',
            'firm[streetAddress]' => '123 Cheese St.',
            'firm[city]'          => '',
            'firm[gender]'        => 'U',
            'firm[startDate]'     => '1972',
            'firm[endDate]'       => '1999',
            'firm[finalcheck]'    => 1,
        ])
        ;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/firm/1'));
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese.")')->count());
    }

    public function testAnonNew() : void {

        $crawler = $this->client->request('GET', '/firm/new');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserNew() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/firm/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login('user.admin');
        $formCrawler = $this->client->request('GET', '/firm/new');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'firm[name]'          => 'Cheese.',
            'firm[streetAddress]' => '123 Cheese St.',
            'firm[city]'          => '',
            'firm[gender]'        => 'U',
            'firm[startDate]'     => '1972',
            'firm[endDate]'       => '1999',
            'firm[finalcheck]'    => 1,
        ])
        ;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese.")')->count());
    }

    public function testAnonDelete() : void {

        $crawler = $this->client->request('GET', '/firm/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserDelete() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/firm/1/delete');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        $preCount = count($this->entityManager->getRepository(Firm::class)->findAll());
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/firm/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->entityManager->clear();
        $postCount = count($this->entityManager->getRepository(Firm::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }

    public function testAnonSearch() : void {
        $repo = $this->createMock(FirmRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('firm.1')]);

        $this->client->disableReboot();
        $this->client->getContainer()->set(FirmRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/firm/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'firm_search[name]' => 'adventures',
        ])
        ;

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("StreetAddress 1")')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(FirmRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('firm.1')]);
        $this->login('user.user');
        $this->client->disableReboot();
        $this->client->getContainer()->set(FirmRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/firm/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'firm_search[name]' => 'adventures',
        ])
        ;

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("StreetAddress 1")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(FirmRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('firm.1')]);
        $this->login('user.admin');
        $this->client->disableReboot();
        $this->client->getContainer()->set(FirmRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/firm/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'firm_search[name]' => 'adventures',
        ])
        ;

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("StreetAddress 1")')->count());
    }
}
