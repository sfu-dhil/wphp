<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\App\Controller;

use Nines\BlogBundle\DataFixtures\PageFixtures;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class DefaultControllerTest extends ControllerBaseCase {
    protected function fixtures() : array {
        return [
            PageFixtures::class,
            UserFixtures::class,
        ];
    }

    public function testIndex() : void {
        $crawler = $this->client->request('GET', '/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
