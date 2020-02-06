<?php

namespace AppBundle\Tests\AppBundle\Controller;

use Nines\BlogBundle\DataFixtures\ORM\LoadPage;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class DefaultControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return array(
            LoadPage::class,
            LoadUser::class,
        );
    }

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
