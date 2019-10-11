<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\Role;
use AppBundle\Entity\TitleRole;
use PHPUnit\Framework\TestCase;

/**
 * Description of PersonTest.
 */
class PersonTest extends TestCase {
    //put your code here

    /**
     * @dataProvider getDobData
     *
     * @param mixed $expected
     * @param mixed $date
     */
    public function testGetDob($expected, $date) {
        $person = new Person();
        $person->setDob($date);
        $this->AssertEquals($expected, $person->getDob());
    }

    public function getDobData() {
        return array(
            array(null, '0000-00-00'),
            array('1982-11-06', '1982-11-06'),
            array(null, null),
        );
    }

    /**
     * @dataProvider getDodData
     *
     * @param mixed $expected
     * @param mixed $date
     */
    public function testGetDod($expected, $date) {
        $person = new Person();
        $person->setDod($date);
        $this->AssertEquals($expected, $person->getDod());
    }

    public function getDodData() {
        return array(
            array(null, '0000-00-00'),
            array('1982-11-06', '1982-11-06'),
            array(null, null),
        );
    }

    public function testGetTitleRoles() {
        $person = new Person();
        $role = new Role();
        $titleRole = new TitleRole();

        $var = 'Jane Taylor';

        $role->setName($var);
        $titleRole->setRole($role);
        $person->addTitleRole($titleRole);

        $this->AssertEquals(1, count($person->getTitleRoles()));

        $person->removeTitleRole($titleRole);

        $this->AssertEquals(0, count($person->getTitleRoles()));
    }

    public function testToString() {
        $person = new Person();

        $firstName = 'Jane';
        $lastName = 'Taylor';
        $expected = 'Taylor, Jane';

        $person->setFirstName($firstName);
        $person->setLastName($lastName);

        $this->AssertEquals($expected, $person->__toString());
    }
}
