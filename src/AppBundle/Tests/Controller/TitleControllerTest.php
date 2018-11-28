<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Title;
use AppBundle\DataFixtures\ORM\LoadTitle;
use Nines\UtilBundle\Tests\Util\BaseTestCase;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;

class TitleControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadTitle::class
        ];
    }

    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }

    public function testUserIndex() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }

    public function testAdminIndex() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('New')->count());
    }

    public function testAnonTypeahead() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/typeahead?q=title');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertContains('Redirecting', $client->getResponse()->getContent());
    }

    public function testUserTypeahead() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/typeahead?q=title');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('text/plain; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertContains('Access denied.', $client->getResponse()->getContent());
    }

    public function testAdminTypeahead() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/typeahead?q=title');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('application/json', $client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($client->getResponse()->getContent());
        $this->assertEquals(4, count($json));
    }

    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('Edit')->count());
        $this->assertEquals(1, $crawler->selectLink('Delete')->count());
    }
    public function testAnonEdit() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/1/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserEdit() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/1/edit');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/title/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'title[title]' => 'The Book of Cheese.',
            'title[editionNumber]' => 1,
            'title[signedAuthor]' => 'Testy McAuthor',
            // 'title[titleRoles]' => 1,
            // 'title[titleFirmroles]' => 0,
            'title[pseudonym]' => 'Author',
            'title[imprint]' => 'Cheese Publishers',
            'title[selfpublished]' => 0,
            'title[pubdate]' => '1932',
            'title[genre]' => 0,
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
            'title[source]' => 0,
            'title[sourceId]' => 'ID',
            'title[source2]' => 0,
            'title[source2Id]' => 'ID2',
            'title[shelfmark]' => '',
            'title[checked]' => 1,
            'title[finalcheck]' => 1,
            'title[notes]' => 'It is about cheese.'
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/title/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('h1:contains("The Book of Cheese.")')->count());
    }

    public function testAnonNew() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/new');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserNew() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $formCrawler = $client->request('GET', '/title/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'title[title]' => 'The Book of Cheese.',
            'title[editionNumber]' => 1,
            'title[signedAuthor]' => 'Testy McAuthor',
            // 'title[titleRoles]' => 1,
            // 'title[titleFirmroles]' => 0,
            'title[pseudonym]' => 'Author',
            'title[imprint]' => 'Cheese Publishers',
            'title[selfpublished]' => 0,
            'title[pubdate]' => '1932',
            'title[genre]' => 0,
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
            'title[source]' => 0,
            'title[sourceId]' => 'ID',
            'title[source2]' => 0,
            'title[source2Id]' => 'ID2',
            'title[shelfmark]' => '',
            'title[checked]' => 1,
            'title[finalcheck]' => 1,
            'title[notes]' => 'It is about cheese.'
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('h1:contains("The Book of Cheese.")')->count());
    }

    public function testAnonDelete() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/title/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testUserDelete() {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/title/1/delete');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() {
        self::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $preCount = count($em->getRepository(Title::class)->findAll());
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/title/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $em->clear();
        $postCount = count($em->getRepository(Title::class)->findAll());
        $this->assertEquals($preCount - 1, $postCount);
    }

}
