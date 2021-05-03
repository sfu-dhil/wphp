<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Entity;

use App\Entity\Firm;
use App\Entity\Firmrole;
use App\Entity\TitleFirmrole;
use PHPUnit\Framework\TestCase;

/**
 * Description of FirmTest.
 */
class FirmTest extends TestCase {
    /**
     * @dataProvider getStartDateData
     *
     * @param mixed $expected
     * @param mixed $date
     */
    public function testGetStartDate($expected, $date) : void {
        $firm = new Firm();
        $firm->setStartDate($date);
        $this->AssertEquals($expected, $firm->getStartDate());
    }

    public function getStartDateData() {
        return [
            [null, '0000-00-00'],
            ['1982-11-06', '1982-11-06'],
            [null, null],
        ];
    }

    /**
     * @dataProvider getEndDateData
     *
     * @param mixed $expected
     * @param mixed $date
     */
    public function testGetEndDate($expected, $date) : void {
        $firm = new Firm();
        $firm->setEndDate($date);
        $this->AssertEquals($expected, $firm->getEndDate());
    }

    public function getEndDateData() {
        return [
            [null, '0000-00-00'],
            ['1982-11-06', '1982-11-06'],
        ];
    }

    public function testGetTitleFirmroles() : void {
        $firm = new Firm();
        $firmRole = new Firmrole();
        $titleFirmRole = new TitleFirmrole();

        $var = 'Jane Taylor';

        $firmRole->setName($var);
        $titleFirmRole->setFirmrole($firmRole);
        $firm->addTitleFirmrole($titleFirmRole);

        $this->assertCount(1, $firm->getTitleFirmroles());

        $firm->removeTitleFirmrole($titleFirmRole);

        $this->assertCount(0, $firm->getTitleFirmroles());
    }
}
