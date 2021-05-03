<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Entity;

use App\Entity\Firmrole;
use App\Entity\Role;
use App\Entity\Title;
use App\Entity\TitleFirmrole;
use App\Entity\TitleRole;
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
    public function testGetTotalPrice($expected, $pence, $shillings, $pounds) : void {
        $title = new Title();
        $title->setPricePence($pence);
        $title->setPriceShilling($shillings);
        $title->setPricePound($pounds);
        $this->assertSame($expected, $title->getTotalPrice());
    }

    public function getTotalPriceData() {
        return [
            // array( expected, pence, shillings, pounds)
            [240, 0, 20, 0],
            [10, 10, 0, 0],
            [240, 0, 0, 1],
            [480, 0, 0, 2],
            [262, 10, 1, 1],
            [250, 10, 0, 1],
            [252, 0, 1, 1],
            [0, 0, 0, 0],
            [24, 12, 1, 0],
            [0, 'I am a chicken', 0, 0],
            [0, '3 chickens', 0, 0],
            [0, 0, '3 chickens', 0],
            [0, 0, 0, '3 chickens'],
            [-240, 0, 0, -1],
            [0, null, null, null],
            [40, 40, null, 0],
            [0, false, 0, 0],
            [12, false, 1, 0],
        ];
    }

    public function testGetTitleRoles() : void {
        $title = new Title();
        $role = new Role();
        $titleRole = new TitleRole();

        $var = 'Jane Taylor';

        $role->setName($var);
        $titleRole->setRole($role);
        $title->addTitleRole($titleRole);

        $this->assertCount(1, $title->getTitleRoles());

        $title->removeTitleRole($titleRole);

        $this->assertCount(0, $title->getTitleRoles());
    }

    public function testGetTitleFirmRoles() : void {
        $title = new Title();
        $firmRole = new Firmrole();
        $titleFirmRole = new TitleFirmrole();

        $var = 'Jane Taylor';

        $firmRole->setName($var);
        $titleFirmRole->setFirmrole($firmRole);
        $title->addTitleFirmrole($titleFirmRole);

        $this->assertCount(1, $title->getTitleFirmroles());

        $title->removeTitleFirmrole($titleFirmRole);

        $this->assertCount(0, $title->getTitleFirmroles());
    }
}
