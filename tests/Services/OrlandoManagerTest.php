<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Services\OrlandoManager;
use Nines\UtilBundle\TestCase\ControllerTestCase;

class OrlandoManagerTest extends ControllerTestCase {
    final public const DATA = 'A_ID = 20384 || STANDARD = Author 2 || ROLE = EDITOR %%% A_ID = 19884 || STANDARD = Other Author 2 || ROLE = AUTHOR';

    private OrlandoManager $manager;

    public function testSanity() : void {
        $this->assertInstanceOf(OrlandoManager::class, $this->manager);
    }

    public function testNullData() : void {
        $this->assertCount(0, $this->manager->getField(null));
    }

    public function testGetField() : void {
        $this->assertSame(['Author 2', 'Other Author 2'], $this->manager->getField(self::DATA));
    }

    public function testGetEmptyField() : void {
        $this->assertSame([], $this->manager->getField(self::DATA, 'cheese'));
    }

    public function testGetNamedField() : void {
        $this->assertSame(['EDITOR', 'AUTHOR'], $this->manager->getField(self::DATA, 'role'));
    }

    protected function setUp() : void {
        parent::setUp();
        $this->manager = self::getContainer()->get(OrlandoManager::class);
    }
}
