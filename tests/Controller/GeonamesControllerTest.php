<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\DataFixtures\GeonamesFixtures;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class GeonamesControllerTest extends ControllerBaseCase
{
    protected function fixtures() : array {
        return [
            UserFixtures::class,
            GeonamesFixtures::class,
        ];
    }

    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/geonames/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserIndex() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/geonames/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminIndex() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/geonames/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAnonTypeahead() : void {
        $crawler = $this->client->request('GET', '/geonames/typeahead?q=name');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $this->client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $this->client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/geonames/typeahead?q=name');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $this->client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/geonames/typeahead?q=name');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(4, $json);
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/geonames/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserShow() : void {
        $this->login('user.user');
        $crawler = $this->client->request('GET', '/geonames/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminShow() : void {
        $this->login('user.admin');
        $crawler = $this->client->request('GET', '/geonames/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
