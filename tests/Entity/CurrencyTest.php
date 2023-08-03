<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Currency;
use PHPUnit\Framework\TestCase;

/**
 * Description of PersonTest.
 */
class CurrencyTest extends TestCase {
    // put your code here

    /**
     * @return array[]
     */
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
    public function testFormat(mixed $code, mixed $symbol, mixed $name, mixed $value, mixed $expected) : void {
        $currency = new Currency();
        $currency->setCode($code);
        $currency->setName($name);
        $currency->setSymbol($symbol);
        $this->assertSame($expected, $currency->format($value));
    }
}
