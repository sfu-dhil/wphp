<?php

namespace AppBundle\Services;

use AppBundle\DataFixtures\ORM\LoadFormat;
use AppBundle\DataFixtures\ORM\LoadPerson;
use AppBundle\DataFixtures\ORM\LoadRole;
use AppBundle\DataFixtures\ORM\LoadSource;
use AppBundle\Entity\EstcMarc;
use AppBundle\Entity\Person;
use AppBundle\Entity\Title;
use AppBundle\Repository\EstcMarcRepository;
use AppBundle\Repository\TitleRepository;
use AppBundle\Repository\TitleSourceRepository;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class EstcMarcImporterTest extends BaseTestCase {
    /**
     * @var EstcMarcImporter
     */
    private $importer;

    protected function getFixtures() {
        return array(
            LoadPerson::class,
            LoadRole::class,
            LoadSource::class,
            LoadFormat::class,
        );
    }

    public function testGetFields() {
        $f1 = new EstcMarc();
        $f1->setTitleId('abc')->setField('100')->setSubfield('a')->setFieldData('Bond, James');
        $f2 = new EstcMarc();
        $f2->setTitleId('abc')->setField('100')->setSubfield('b')->setFieldData('Moneypenny, Miss');

        $repo = $this->createMock(EstcMarcRepository::class);
        $repo->method('findBy')->willReturn(array($f1, $f2));
        $this->importer->setEstcRepo($repo);

        $data = $this->importer->getFields('foo');
        $this->assertCount(2, $data);
        $this->assertEquals(array('100a', '100b'), array_keys($data));
        $this->assertEquals('Bond, James', $data['100a']->getFieldData());
        $this->assertEquals('Moneypenny, Miss', $data['100b']->getFieldData());
    }

    public function testCheckTitle() {
        $repo = $this->createMock(TitleRepository::class);
        $repo->method('findBy')->willReturn(array('a', 'b'));
        $this->importer->setTitleRepo($repo);
        $this->importer->checkTitle('fooo', 'abc123');

        $this->assertCount(1, $this->importer->getMessages());
    }

    public function testCheckTitleId() {
        $repo = $this->createMock(TitleSourceRepository::class);
        $repo->method('findBy')->willReturn(array('a', 'b'));
        $this->importer->setTitleSourceRepo($repo);
        $this->importer->checkTitle('fooo', 'abc123');

        $this->assertCount(1, $this->importer->getMessages());
    }

    /**
     * @dataProvider getDatesData
     *
     * @param mixed $expectedDob
     * @param mixed $expectedDod
     * @param mixed $data
     */
    public function testGetDates($expectedDob, $expectedDod, $data) {
        $f2 = new EstcMarc();
        $f2->setField('100')->setSubfield('d')->setFieldData($data);
        list($dob, $dod) = $this->importer->getDates(array('100d' => $f2));
        $this->assertEquals($expectedDob, $dob);
        $this->assertEquals($expectedDod, $dod);
    }

    public function getDatesData() {
        return array(
            array('1751', '1801', '1751-1801'),
            array('1751', '1801', '1751-1801.'),
            array('1751', '1801', '1751-1801,'),
            array(null, null, null),

            // this is actual data. sigh.
            array('1698', '1709', 'active 1698-1709.'),
            array('1468', '1522', '1468?-1522.'),
            array(null, '1714', '-1714]'),
            array(null, '1147', '-1147?.'),
            array(null, 1716, '-1716.'),
            array('1770', '1820', '1770?-1820?.'),
            array('1770', null, '1770?-.'),
            array(null, '1520', '-1520 or 1521.'),
            array(null, '1783', '-1783 November 17.'),
            array(null, null, '-approximately 1676.'),
            array('1752', '1820', '. 1752-1820.'),
            array('1763', null, '.b. 1763.'),
            array(null, null, '100 B.C.-44 B.C.'),
            array('1142', '1165', '1141 or 1142-1165.'),
            array(null, null, '121-180.'),
            array('1388', '', '1388?.-'),
            array('1580', '1653', '1580-1653)]'),
            array('1590', null, '1590-approximately 1645.'),
            array('1603', null, '1602 or 1603-'),
            array('1605', null, '1605-'),
        );
    }

    public function testGetPerson() {
        $f1 = new EstcMarc();
        $f1->setField('100')->setSubfield('a')->setFieldData('LastName 1, FirstName 1.');
        $f2 = new EstcMarc();
        $f2->setField('100d')->setSubfield('d')->setFieldData('1751-1801.');

        $personCount = $this->em->getRepository(Person::class)->count(array());
        $this->em->clear();

        $person = $this->importer->getPerson(array('100a' => $f1, '100d' => $f2));
        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($personCount, $this->em->getRepository(Person::class)->count(array()));
        $this->assertCount(0, $this->importer->getMessages());
        $this->assertEquals('LastName 1', $person->getLastName());
    }

    public function testGetPersonNoDates() {
        $f1 = new EstcMarc();
        $f1->setField('100')->setSubfield('a')->setFieldData('LastName 1, FirstName 1.');
        $personCount = $this->em->getRepository(Person::class)->count(array());
        $this->em->clear();

        $person = $this->importer->getPerson(array('100a' => $f1));
        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($personCount, $this->em->getRepository(Person::class)->count(array()));
        $this->assertEquals('LastName 1', $person->getLastName());
    }

    public function testGetPersonMultiple() {
        $person = new Person();
        $person->setLastName('LastName 1');
        $person->setFirstName('FirstName 1');
        $this->em->persist($person);
        $this->em->flush();

        $f1 = new EstcMarc();
        $f1->setField('100')->setSubfield('a')->setFieldData('LastName 1, FirstName 1.');
        $personCount = $this->em->getRepository(Person::class)->count(array());
        $this->em->clear();

        $found = $this->importer->getPerson(array('100a' => $f1));
        $this->assertInstanceOf(Person::class, $found);
        $this->assertEquals($personCount, $this->em->getRepository(Person::class)->count(array()));
        $this->assertEquals('LastName 1', $found->getLastName());
        $this->assertCount(1, $this->importer->getMessages());
    }

    public function testGetNewPerson() {
        $f1 = new EstcMarc();
        $f1->setField('100')->setSubfield('a')->setFieldData('Doolittle, Eliza');
        $f2 = new EstcMarc();
        $f2->setField('100d')->setSubfield('d')->setFieldData('1701-1761.');
        $personCount = $this->em->getRepository(Person::class)->count(array());
        $this->em->clear();

        $person = $this->importer->getPerson(array('100a' => $f1, '100d' => $f2));
        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($personCount + 1, $this->em->getRepository(Person::class)->count(array()));
        $this->assertEquals('Doolittle', $person->getLastName());
    }

    public function testAddAuthor() {
        $title = new Title();
        $person = new Person();
        $this->importer->addAuthor($title, $person);
        $this->assertCount(1, $title->getTitleRoles());
        $roles = $title->getTitleRoles();
        $this->assertNotNull($roles[0]);
        $this->assertEquals('Author', $roles[0]->getRole()->getName());
    }

    /**
     * @dataProvider guessFormatData
     *
     * @param mixed $name
     * @param mixed $data
     */
    public function testGuessFormat($name, $data) {
        $f1 = new EstcMarc();
        $f1->setFieldData($data);
        $format = $this->importer->guessFormat(array('300c' => $f1));
        $this->assertNotNull($format);
        $this->assertEquals($name, $format->getName());
    }

    public function guessFormatData() {
        return array(
            array('octavo', '8vo'),

            // real data. again.
            array('octavo', '16 cm. (8vo)'),
            array('quarto', '(4to and 8vo)'),
        );
    }

    /**
     * @dataProvider guessDimensionsData
     *
     * @param mixed $width
     * @param mixed $height
     * @param mixed $data
     */
    public function testGuessDimensions($width, $height, $data) {
        $f1 = new EstcMarc();
        $f1->setFieldData($data);
        list($w, $h) = $this->importer->guessDimensions(array('300c' => $f1));
        $this->assertEquals($width, $w);
        $this->assertEquals($height, $h);
    }

    public function guessDimensionsData() {
        return array(
            array('10', '15', '10 x 15 cm'),
            array('10', '15', '10cm x 15 cm'),
            array('10', '15', '10x15cm'),
            array('10', '15', '10cmx15 cm'),
            array('10', null, '10 cm.'),
        );
    }

    protected function setUp() : void {
        parent::setUp();
        $this->importer = $this->getContainer()->get(EstcMarcImporter::class);
    }

//    public function testImport() {
//        $repo = $this->createMock(EstcMarcRepository::class);
//        $repo->method('findBy')->willReturn(array(
//
//        ));
//    }
//    245a, 245b, 300a, 260b, 260c, 001
}
