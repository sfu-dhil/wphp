<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Entity;

use App\Entity\Currency;
use App\Entity\Person;
use App\Entity\Role;
use App\Entity\TitleRole;
use PHPUnit\Framework\TestCase;

/**
 * Description of PersonTest.
 */
class CurrencyTest extends TestCase {
    //put your code here

    public function getData() {
        return [
            ['GBP', '£', 'Pound Sterling', 1.00, '£1.00'],
            [null, '£', 'Pound Sterling', 1.00, '£1.00'],
            [null, null, 'Pound Sterling', 1.00, '1.00 Pound Sterling'],

            ['GBP', '£', 'Pound Sterling', 1.00, '£1.00'],
            ['', '£', 'Pound Sterling', 1.00, '£1.00'],
            ['', '', 'Pound Sterling', 1.00, '1.00 Pound Sterling'],

            ['GBP', '', '', 1.00, '£1.00'],
            [null, '£', '', 1.00, '£1.00'],
            [null, null, '', 1.00, '1.00'],

            ['GBP', '', '', 1.00, '£1.00'],
            ['', '£', '', 1.00, '£1.00'],
            ['', '', '', 1.00, '1.00'],

            ['USD', '$', 'US Dollar', 1.00, 'US$1.00'],
            [null, '$', 'US Dollar', 1.00, '$1.00'],
            [null, null, 'US Dollar', 1.00, '1.00 US Dollar'],

            ['CAD', '$', 'Canadian Dollar', 1.00, '$1.00'],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testFormat($code, $symbol, $name, $value, $expected) {
        $currency = new Currency();
        $currency->setCode($code);
        $currency->setName($name);
        $currency->setSymbol($symbol);
        $this->assertEquals($expected, $currency->format($value));
    }

}
