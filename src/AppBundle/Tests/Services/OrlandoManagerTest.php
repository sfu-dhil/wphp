<?php

namespace AppBundle\Tests\Services;


use AppBundle\DataFixtures\ORM\LoadOrlandoBiblio;
use AppBundle\Services\OrlandoManager;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class SourceLinkerTest extends BaseTestCase
{

    const DATA = 'A_ID = 20384 || STANDARD = Author 2 || ROLE = EDITOR %%% A_ID = 19884 || STANDARD = Other Author 2 || ROLE = AUTHOR';

    private $manager;

    protected function getFixtures() {
        return array(
            LoadOrlandoBiblio::class,
        );
    }

    protected function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get(OrlandoManager::class);
    }

    public function testSanity() {
        $this->assertInstanceOf(OrlandoManager::class, $this->manager);
    }

    public function testNullData() {
        $this->assertCount(0, $this->manager->getField(null));
    }

    public function testGetField() {
        $this->assertEquals(['Author 2', 'Other Author 2'], $this->manager->getField(self::DATA));
    }

    public function testGetEmptyField() {
        $this->assertEquals([], $this->manager->getField(self::DATA, 'cheese'));
    }

    public function testGetNamedField() {
        $this->assertEquals(['EDITOR', 'AUTHOR'], $this->manager->getField(self::DATA, 'role'));
    }



}
