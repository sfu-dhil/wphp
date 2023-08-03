<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Person;
use App\Entity\Role;
use App\Entity\TitleRole;
use PHPUnit\Framework\TestCase;

/**
 * Description of PersonTest.
 */
class PersonTest extends TestCase {
    // put your code here
    /**
     * @dataProvider getDobData
     */
    public function testGetDob(mixed $expected, mixed $date) : void {
        $person = new Person();
        $person->setDob($date);
        $this->AssertEquals($expected, $person->getDob());
    }

    /**
     * @return array[]
     */
    public function getDobData() {
        return [
            [null, '0000-00-00'],
            ['1982-11-06', '1982-11-06'],
            [null, null],
        ];
    }

    /**
     * @dataProvider getDodData
     */
    public function testGetDod(mixed $expected, mixed $date) : void {
        $person = new Person();
        $person->setDod($date);
        $this->AssertEquals($expected, $person->getDod());
    }

    /**
     * @return array[]
     */
    public function getDodData() {
        return [
            [null, '0000-00-00'],
            ['1982-11-06', '1982-11-06'],
            [null, null],
        ];
    }

    public function testGetTitleRoles() : void {
        $person = new Person();
        $role = new Role();
        $titleRole = new TitleRole();

        $var = 'Jane Taylor';

        $role->setName($var);
        $titleRole->setRole($role);
        $person->addTitleRole($titleRole);

        $this->assertCount(1, $person->getTitleRoles());

        $person->removeTitleRole($titleRole);

        $this->assertCount(0, $person->getTitleRoles());
    }

    public function testToString() : void {
        $person = new Person();

        $firstName = 'Jane';
        $lastName = 'Taylor';
        $expected = 'Taylor, Jane';

        $person->setFirstName($firstName);
        $person->setLastName($lastName);

        $this->AssertEquals($expected, $person->__toString());
    }
}
