<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Source;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\TestCase\ControllerTestCase;

class SourceControllerTest extends ControllerTestCase {
    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/source/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Source')->count());
    }

    public function testUserIndex() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/source/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Source')->count());
    }

    public function testAdminIndex() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/source/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Add Source')->count());
    }

    public function testAnonTypeahead() : void {
        $crawler = $this->client->request('GET', '/source/typeahead?q=name');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $this->client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $this->client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/source/typeahead?q=name');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $this->client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/source/typeahead?q=name');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);
        $this->assertCount(4, $json);
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/source/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/source/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/source/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/source/1/edit');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserEdit() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/source/1/edit');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/source/1/edit');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'source[name]' => 'Cheese',
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/source/1'));
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese")')->count());
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/source/new');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserNew() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/source/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/source/new');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'source[name]' => 'Cheese',
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Cheese")')->count());
    }

    public function testAnonDelete() : void {
        $crawler = $this->client->request('GET', '/source/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserDelete() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/source/1/delete');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        $source = $this->em->find(Source::class, 1);
        foreach ($source->getFirmSources() as $fs) {
            $this->em->remove($fs);
        }
        foreach ($source->getTitleSources() as $ts) {
            $this->em->remove($ts);
        }

        $preCount = count($this->em->getRepository(Source::class)->findAll());
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/source/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->em->clear();
        $postCount = count($this->em->getRepository(Source::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);

        $this->reset();
    }
}
