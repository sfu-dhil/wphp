<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadGeonames;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class GeonamesControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadGeonames::class,
        ];
    }

    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/geonames/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testUserIndex() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/geonames/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testAdminIndex() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/geonames/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testAnonTypeahead() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/geonames/typeahead?q=name');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsStringIgnoringCase('Redirecting', $client->getResponse()->getContent());
    }

    public function testUserTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/geonames/typeahead?q=name');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Access denied.', $client->getResponse()->getContent());
    }

    public function testAdminTypeahead() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/geonames/typeahead?q=name');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));
        $json = json_decode($client->getResponse()->getContent());
        $this->assertSame(4, count($json));
    }

    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/geonames/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testUserShow() : void {
        $client = $this->makeClient([
            'username' => 'user@example.com',
            'password' => 'secret',
        ]);
        $crawler = $client->request('GET', '/geonames/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testAdminShow() : void {
        $client = $this->makeClient([
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ]);
        $crawler = $client->request('GET', '/geonames/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
