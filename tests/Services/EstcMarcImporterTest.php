<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\EstcMarc;
use App\Repository\EstcMarcRepository;
use App\Repository\TitleRepository;
use App\Repository\TitleSourceRepository;
use Nines\UtilBundle\TestCase\ControllerTestCase;

class EstcMarcImporterTest extends ControllerTestCase {
    /**
     * @var EstcMarcImporter
     */
    private $importer;

    public function testGetFields() : void {
        $f1 = new EstcMarc();
        $f1->setTitleId(123)->setField('100')->setSubfield('a')->setFieldData('Bond, James');
        $f2 = new EstcMarc();
        $f2->setTitleId(123)->setField('100')->setSubfield('b')->setFieldData('Moneypenny, Miss');

        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('findBy')->willReturn([$f1, $f2]);
        $this->importer->setEstcRepo($repo);

        $data = $this->importer->getFields('foo');
        $this->assertCount(2, $data);
        $this->assertSame(['100a', '100b'], array_keys($data));
        $this->assertSame('Bond, James', $data['100a']->getFieldData());
        $this->assertSame('Moneypenny, Miss', $data['100b']->getFieldData());
    }

    public function testCheckTitle() : void {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('findBy')->willReturn(['a', 'b']);
        $this->importer->setTitleRepo($repo);
        $this->importer->checkTitle('fooo', 'abc123');

        $this->assertCount(1, $this->importer->getMessages());
    }

    public function testCheckTitleId() : void {
        $repo = $this->createMock(TitleSourceRepository::class);
        $repo->method('findBy')->willReturn(['a', 'b']);
        $this->importer->setTitleSourceRepo($repo);
        $this->importer->checkTitle('fooo', 'abc123');

        $this->assertCount(1, $this->importer->getMessages());
    }

    /**
     * @dataProvider getDatesData
     */
    public function testGetDates(mixed $expectedDob, mixed $expectedDod, mixed $data) : void {
        $f2 = new EstcMarc();
        $f2->setField('100')->setSubfield('d')->setFieldData($data);
        list($dob, $dod) = $this->importer->getDates(['100d' => $f2]);
        $this->assertSame($expectedDob, $dob);
        $this->assertSame($expectedDod, $dod);
    }

    /**
     * @return array[]
     */
    public function getDatesData() {
        return [
            ['1751', '1801', '1751-1801'],
            ['1751', '1801', '1751-1801.'],
            ['1751', '1801', '1751-1801,'],
            [null, null, null],

            // this is actual data. sigh.
            ['1698', '1709', 'active 1698-1709.'],
            ['1468', '1522', '1468?-1522.'],
            [null, '1714', '-1714]'],
            [null, '1147', '-1147?.'],
            [null, '1716', '-1716.'],
            ['1770', '1820', '1770?-1820?.'],
            ['1770', null, '1770?-.'],
            [null, '1520', '-1520 or 1521.'],
            [null, '1783', '-1783 November 17.'],
            [null, null, '-approximately 1676.'],
            ['1752', '1820', '. 1752-1820.'],
            ['1763', null, '.b. 1763.'],
            [null, null, '100 B.C.-44 B.C.'],
            ['1142', '1165', '1141 or 1142-1165.'],
            [null, null, '121-180.'],
            ['1388', null, '1388?.-'],
            ['1580', '1653', '1580-1653)]'],
            ['1590', null, '1590-approximately 1645.'],
            ['1603', null, '1602 or 1603-'],
            ['1605', null, '1605-'],
        ];
    }

    /**
     * @dataProvider guessFormatData
     */
    public function testGuessFormat(mixed $name, mixed $data) : void {
        $f1 = new EstcMarc();
        $f1->setFieldData($data);
        $format = $this->importer->guessFormat(['300c' => $f1]);
        $this->assertNotNull($format);
        $this->assertSame($name, $format->getName());
    }

    /**
     * @return string[][]
     */
    public function guessFormatData() {
        return [
            ['octavo', '8vo'],

            // real data. again.
            ['octavo', '16 cm. (8vo)'],
            ['quarto', '(4to and 8vo)'],
        ];
    }

    /**
     * @dataProvider guessDimensionsData
     */
    public function testGuessDimensions(mixed $width, mixed $height, mixed $data) : void {
        $f1 = new EstcMarc();
        $f1->setFieldData($data);
        list($w, $h) = $this->importer->guessDimensions(['300c' => $f1]);
        $this->assertSame($width, $w);
        $this->assertSame($height, $h);
    }

    /**
     * @return array[]
     */
    public function guessDimensionsData() {
        return [
            ['10', '15', '10 x 15 cm'],
            ['10', '15', '10cm x 15 cm'],
            ['10', '15', '10x15cm'],
            ['10', '15', '10cmx15 cm'],
            ['10', null, '10 cm.'],
        ];
    }

    protected function setUp() : void {
        parent::setUp();
        $this->importer = self::getContainer()->get(EstcMarcImporter::class);
    }

    //    public function testImport() {
    //        $repo = $this->createMock(EstcMarcRepository::class);
    //        $repo->method('findBy')->willReturn(array(
    //
    //        ));
    //    }
    //    245a, 245b, 300a, 260b, 260c, 001
}
