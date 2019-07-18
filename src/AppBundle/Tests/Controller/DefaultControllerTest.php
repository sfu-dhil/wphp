<?php

namespace AppBundle\Tests\AppBundle\Controller;

use Nines\UtilBundle\Tests\Util\BaseTestCase;

class DefaultControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
