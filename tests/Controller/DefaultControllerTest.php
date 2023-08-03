<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Nines\UtilBundle\TestCase\ControllerTestCase;

class DefaultControllerTest extends ControllerTestCase {
    public function testIndex() : void {
        $crawler = $this->client->request('GET', '/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
