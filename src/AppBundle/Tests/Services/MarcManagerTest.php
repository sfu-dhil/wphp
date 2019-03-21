<?php
/**
 * Created by PhpStorm.
 * User: mjoyce
 * Date: 2019-03-20
 * Time: 09:54
 */

namespace AppBundle\Tests\Services;

use AppBundle\DataFixtures\ORM\LoadEstcMarc;
use AppBundle\DataFixtures\ORM\LoadMarcSubfieldStructure;
use AppBundle\DataFixtures\ORM\LoadMarcTagStructure;
use AppBundle\DataFixtures\ORM\LoadOsborneMarc;
use AppBundle\Entity\EstcMarc;
use AppBundle\Entity\OsborneMarc;
use AppBundle\Services\MarcManager;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class MarcManagerTest extends BaseTestCase
{

    private $manager;

    protected function getFixtures()
    {
        return [
            LoadEstcMarc::class,
            LoadOsborneMarc::class,
            LoadMarcTagStructure::class,
            LoadMarcSubfieldStructure::class,
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get(MarcManager::class);
    }

    public function testSanity() {
        $this->assertInstanceOf(MarcManager::class, $this->manager);
    }

    public function testGetEstcTitle() {
        $estcMarc = $this->getReference('estc.0.0.0');
        $this->assertEquals('ESTC Title 0', $this->manager->getTitle($estcMarc));
    }

    public function testGetOsborneTitle() {
        $estcMarc = $this->getReference('osborne.0.0.0');
        $this->assertEquals('OSBORNE Title 0', $this->manager->getTitle($estcMarc));
    }

    public function testGetEstcAuthor() {
        $estcMarc = $this->getReference('estc.0.0.0');
        $this->assertEquals('Estc Field Data 0 100a', $this->manager->getAuthor($estcMarc));
    }

    public function testGetOsborneAuthor() {
        $estcMarc = $this->getReference('osborne.0.0.0');
        $this->assertEquals('Osborne Field Data 0 0 0', $this->manager->getAuthor($estcMarc));
    }

    public function testGetEstcData() {
        $estcMarc = $this->getReference('estc.0.0.0');
        $data = $this->manager->getData($estcMarc);
        $this->assertCount(203, $data);
    }

    public function testGetDataData() {
        $estcMarc = $this->getReference('osborne.0.0.0');
        $data = $this->manager->getData($estcMarc);
        $this->assertCount(203, $data);
    }

    public function testGetUnknownEstcFieldName() {
        $field = new EstcMarc();
        $field->setField('999');
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('999', $name);
    }

    public function testGetEstcFieldName() {
        $field = new EstcMarc();
        $field->setField(100);
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('Tag 0', $name);
    }

    public function testGetUnknownOsborneFieldName() {
        $field = new OsborneMarc();
        $field->setField('999');
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('999', $name);
    }

    public function testGetOsborneFieldName() {
        $field = new OsborneMarc();
        $field->setField(100);
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('Tag 0', $name);
    }

    public function testGetUnknownEstcSubfieldName() {
        $field = new EstcMarc();
        $field->setField(100);
        $field->setSubfield('z');
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('100z', $name);
    }

    public function testGetEstcSubfieldName() {
        $field = new EstcMarc();
        $field->setField(100);
        $field->setSubfield('a');
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('Field 100a', $name);
    }

    public function testGetUnknownOsborneSubfieldName() {
        $field = new OsborneMarc();
        $field->setField(100);
        $field->setSubfield('z');
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('100z', $name);
    }

    public function testGetOsborneSubfieldName() {
        $field = new OsborneMarc();
        $field->setField(100);
        $field->setSubfield('a');
        $name = $this->manager->getFieldName($field);
        $this->assertEquals('Field 100a', $name);
    }
}
