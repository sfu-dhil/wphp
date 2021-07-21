<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Services;

use App\DataFixtures\EstcMarcFixtures;
use App\DataFixtures\MarcSubfieldStructureFixtures;
use App\DataFixtures\MarcTagStructureFixtures;
use App\DataFixtures\OsborneMarcFixtures;
use App\Entity\EstcMarc;
use App\Entity\OsborneMarc;
use App\Services\MarcManager;
use Nines\UtilBundle\Tests\ControllerBaseCase;

class MarcManagerTest extends ControllerBaseCase {
    private $manager;

    protected function fixtures() : array {
        return [
            EstcMarcFixtures::class,
            OsborneMarcFixtures::class,
            MarcTagStructureFixtures::class,
            MarcSubfieldStructureFixtures::class,
        ];
    }

    public function testSanity() : void {
        $this->assertInstanceOf(MarcManager::class, $this->manager);
    }

    public function testGetEstcTitle() : void {
        $estcMarc = $this->getReference('estc.0.0.0');
        $this->assertSame('ESTC Title 0', $this->manager->getTitle($estcMarc));
    }

    public function testGetOsborneTitle() : void {
        $estcMarc = $this->getReference('osborne.0.0.0');
        $this->assertSame('OSBORNE Title 0', $this->manager->getTitle($estcMarc));
    }

    public function testGetEstcAuthor() : void {
        $estcMarc = $this->getReference('estc.0.0.0');
        $this->assertSame('Estc Field Data 0 100a', $this->manager->getAuthor($estcMarc));
    }

    public function testGetOsborneAuthor() : void {
        $estcMarc = $this->getReference('osborne.0.0.0');
        $this->assertSame('Osborne Field Data 0 0 0', $this->manager->getAuthor($estcMarc));
    }

    public function testGetEstcData() : void {
        $estcMarc = $this->getReference('estc.0.0.0');
        $data = $this->manager->getData($estcMarc);
        $this->assertCount(203, $data);
    }

    public function testGetDataData() : void {
        $estcMarc = $this->getReference('osborne.0.0.0');
        $data = $this->manager->getData($estcMarc);
        $this->assertCount(203, $data);
    }

    public function testGetUnknownEstcFieldName() : void {
        $field = new EstcMarc();
        $field->setField('999');
        $name = $this->manager->getFieldName($field);
        $this->assertSame('999', $name);
    }

    public function testGetEstcFieldName() : void {
        $field = new EstcMarc();
        $field->setField(100);
        $name = $this->manager->getFieldName($field);
        $this->assertSame('Tag 0', $name);
    }

    public function testGetUnknownOsborneFieldName() : void {
        $field = new OsborneMarc();
        $field->setField('999');
        $name = $this->manager->getFieldName($field);
        $this->assertSame('999', $name);
    }

    public function testGetOsborneFieldName() : void {
        $field = new OsborneMarc();
        $field->setField(100);
        $name = $this->manager->getFieldName($field);
        $this->assertSame('Tag 0', $name);
    }

    public function testGetUnknownEstcSubfieldName() : void {
        $field = new EstcMarc();
        $field->setField(100);
        $field->setSubfield('z');
        $name = $this->manager->getFieldName($field);
        $this->assertSame('100z', $name);
    }

    public function testGetEstcSubfieldName() : void {
        $field = new EstcMarc();
        $field->setField(100);
        $field->setSubfield('a');
        $name = $this->manager->getFieldName($field);
        $this->assertSame('Field 100a', $name);
    }

    public function testGetUnknownOsborneSubfieldName() : void {
        $field = new OsborneMarc();
        $field->setField(100);
        $field->setSubfield('z');
        $name = $this->manager->getFieldName($field);
        $this->assertSame('100z', $name);
    }

    public function testGetOsborneSubfieldName() : void {
        $field = new OsborneMarc();
        $field->setField(100);
        $field->setSubfield('a');
        $name = $this->manager->getFieldName($field);
        $this->assertSame('Field 100a', $name);
    }

    protected function setUp() : void {
        parent::setUp();
        $this->manager = self::$container->get(MarcManager::class);
    }
}
