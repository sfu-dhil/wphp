<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Firmrole;
use AppBundle\Entity\Role;
use AppBundle\Entity\Title;
use AppBundle\Entity\TitleFirmrole;
use AppBundle\Entity\TitleRole;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase {
    /**
     * @dataProvider getTotalPriceData
     *
     * @param mixed $expected
     * @param mixed $pence
     * @param mixed $shillings
     * @param mixed $pounds
     */
    public function testGetTotalPrice($expected, $pence, $shillings, $pounds) {
        $title = new Title();
        $title->setPricePence($pence);
        $title->setPriceShilling($shillings);
        $title->setPricePound($pounds);
        $this->assertEquals($expected, $title->getTotalPrice());
    }

    public function getTotalPriceData() {
        return array(
            // array( expected, pence, shillings, pounds)
            array(240, 0, 20, 0),
            array(10, 10, 0, 0),
            array(240, 0, 0, 1),
            array(480, 0, 0, 2),
            array(262, 10, 1, 1),
            array(250, 10, 0, 1),
            array(252, 0, 1, 1),
            array(0, 0, 0, 0),
            array(24, 12, 1, 0),
            array(0, 'I am a chicken', 0, 0),
            array(0, '3 chickens', 0, 0),
            array(0, 0, '3 chickens', 0),
            array(0, 0, 0, '3 chickens'),
            array(-240, 0, 0, -1),
            array(0, null, null, null),
            array(40, 40, null, 0),
            array(0, false, 0, 0),
            array(12, false, 1, 0),
        );
    }

    public function testGetTitleRoles() {
        $title = new Title();
        $role = new Role();
        $titleRole = new TitleRole();

        $var = 'Jane Taylor';

        $role->setName($var);
        $titleRole->setRole($role);
        $title->addTitleRole($titleRole);

        $this->AssertEquals(1, count($title->getTitleRoles()));

        $title->removeTitleRole($titleRole);

        $this->AssertEquals(0, count($title->getTitleRoles()));
    }

    public function testGetTitleFirmRoles() {
        $title = new Title();
        $firmRole = new Firmrole();
        $titleFirmRole = new TitleFirmrole();

        $var = 'Jane Taylor';

        $firmRole->setName($var);
        $titleFirmRole->setFirmrole($firmRole);
        $title->addTitleFirmrole($titleFirmRole);

        $this->AssertEquals(1, count($title->getTitleFirmroles()));

        $title->removeTitleFirmrole($titleFirmRole);

        $this->AssertEquals(0, count($title->getTitleFirmroles()));
    }
}
