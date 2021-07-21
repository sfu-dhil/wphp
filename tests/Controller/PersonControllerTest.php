<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\PersonFixtures;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class PersonControllerTest extends ControllerBaseCase {
    protected function fixtures() : array {
        return [
            UserFixtures::class,
            PersonFixtures::class,
        ];
    }

    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/person/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Person')->count());
    }

    public function testUserIndex() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/person/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Add Person')->count());
    }

    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/person/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Add Person')->count());
    }

    public function testAnonTypeahead() : void {
        $crawler = $this->client->request('GET', '/person/typeahead?q=name');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $this->client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $this->client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/person/typeahead?q=name');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $this->client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/person/typeahead?q=name');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(4, $json);
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/person/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/person/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/person/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/person/1/edit');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserEdit() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/person/1/edit');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $this->login('user.admin');
        $formCrawler = $this->client->request('GET', '/person/1/edit');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'person[lastName]' => 'McName',
            'person[firstName]' => 'Testy',
            'person[title]' => '',
            'person[gender]' => 'F',
            'person[dob]' => '1921',
            'person[cityOfBirth]' => 1,
            'person[dod]' => '1999',
            'person[cityOfDeath]' => 1,
            'person[notes]' => 'New Notes',
            'person[finalcheck]' => 1,
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/person/1'));
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Testy")')->count());
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/person/new');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserNew() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/person/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login('user.admin');
        $formCrawler = $this->client->request('GET', '/person/new');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'person[lastName]' => 'McName',
            'person[firstName]' => 'Testy',
            'person[title]' => '',
            'person[gender]' => 'F',
            'person[dob]' => '1921',
            'person[cityOfBirth]' => '',
            'person[dod]' => '1999',
            'person[cityOfDeath]' => '',
            'person[notes]' => 'New Notes',
            'person[finalcheck]' => 1,
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("Testy")')->count());
    }

    public function testAnonDelete() : void {
        $crawler = $this->client->request('GET', '/person/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testUserDelete() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/person/1/delete');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        $preCount = count($this->entityManager->getRepository(Person::class)->findAll());
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/person/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->entityManager->clear();
        $postCount = count($this->entityManager->getRepository(Person::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }

    public function testAnonSearch() : void {
        $repo = $this->createMock(PersonRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('person.1')]);

        $this->client->disableReboot();
        $this->client->getContainer()->set(PersonRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/person/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'person_search[name]' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("LastName 1")')->count());
    }

    public function testUserSearch() : void {
        $repo = $this->createMock(PersonRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('person.1')]);
        $this->login('user.user');
        $this->client->disableReboot();
        $this->client->getContainer()->set(PersonRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/person/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'person_search[name]' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("LastName 1")')->count());
    }

    public function testAdminSearch() : void {
        $repo = $this->createMock(PersonRepository::class);
        $repo->method('buildSearchQuery')->willReturn([$this->getReference('person.1')]);
        $this->login('user.admin');
        $this->client->disableReboot();
        $this->client->getContainer()->set(PersonRepository::class, $repo);

        $formCrawler = $this->client->request('GET', '/person/search');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $form = $formCrawler->selectButton('Search')->form([
            'person_search[name]' => 'adventures',
        ]);

        $responseCrawler = $this->client->submit($form);
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("LastName 1")')->count());
    }
}
