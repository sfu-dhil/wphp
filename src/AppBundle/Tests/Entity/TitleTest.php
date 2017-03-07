<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Title;

class TitleTest extends \PHPUnit_Framework_TestCase {
	
	public function testStuff() {
		$this->assertTrue(true);
	}
	
	/**
	 * @dataProvider testLSDData
	 */
	public function testLSD($expected, $pence, $shillings, $pounds) {
		$title = new Title();
		$title->setPricePence($pence);
		$title->setPriceShilling($shillings);
		$title->setPricePound($pounds);
		$this->assertEquals($expected, $title->getTotalPrice());
	}
	
	public function testLSDData(){
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
			array(0, "I am a chicken", 0, 0),
			array(0, "3 chickens", 0, 0),
			array(0, 0, "3 chickens", 0),
			array(0, 0, 0, "3 chickens"),
			array(-240, 0, 0, -1),
			array(0, null, null, null),
			array(40, 40, null, 0),
			array(0, false, 0, 0),
			array(12, false, 1, 0)
		);
	}
}