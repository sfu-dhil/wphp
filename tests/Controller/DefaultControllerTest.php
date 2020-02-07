<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\AppBundle\Controller;

use Nines\BlogBundle\DataFixtures\ORM\LoadPage;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class DefaultControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadPage::class,
            LoadUser::class,
        ];
    }

    public function testIndex() : void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
