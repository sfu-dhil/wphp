<?php

namespace Nines\UtilBundle\Tests\Entity;

use Nines\UtilBundle\Services\WordTrim;
use PHPUnit_Framework_TestCase;

class WordTrimTest extends PHPUnit_Framework_TestCase {

    private $trimmer;
    
    public function setUp() {
        parent::setUp();
        $this->trimmer = new WordTrim();
    }
 
    /**
     * @dataProvider trimData
     */
    public function testTrim($expected, $len, $string) {
        $this->assertEquals($expected, $this->trimmer->trim($string, $len));
    }
    
    public function trimData() {
        return [
            ["This is a...", 3, "This is a test of the emergency broadcast system."],
            ["This is a...", 3, "   This is a test of the emergency broadcast system."],
            ["This. is- a...", 3, "This. is- a test of the emergency broadcast system."],
            ["This is a...", 3, "<p>This <b>is</b> a test of the emergency broadcast system."],
            ["This is a...", 3, "This&nbsp;is a test of the emergency broadcast system."],
            ["Thés is a...", 3, "Th&eacute;s is a test of the emergency broadcast system."],
            ["Thés iſ a...", 3, "Thés iſ a test of the emergency broadcast system."],
        ];
    }
}
