<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Services;

use App\DataFixtures\EnFixtures;
use App\DataFixtures\EstcMarcFixtures;
use App\DataFixtures\JacksonFixtures;
use App\DataFixtures\OrlandoBiblioFixtures;
use App\DataFixtures\OsborneMarcFixtures;
use App\Entity\Source;
use App\Services\RoleChecker;
use App\Services\SourceLinker;
use Nines\UtilBundle\TestCase\ControllerTestCase;

class SourceLinkerTest extends ControllerTestCase {
    /**
     * @var SourceLinker
     */
    private $linker;

    private $checker;

    public function testSanity() : void {
        $this->assertInstanceOf(SourceLinker::class, $this->linker);
    }

    public function testEstcAnon() : void {
        $this->checker->method('hasRole')->willReturn(false);
        $this->assertNull($this->linker->estc('abc'));
    }

    public function testEstcUser() : void {
        $this->assertStringEndsWith('resource/estc/1', $this->linker->estc('abc-0'));
    }

    public function testEstcNotFound() : void {
        $this->assertNull($this->linker->estc('abc-123456'));
    }

    public function testOrlandoAnon() : void {
        $this->checker->method('hasRole')->willReturn(false);
        $this->assertNull($this->linker->orlando('abc'));
    }

    public function testOrlandoUser() : void {
        $this->assertStringEndsWith('resource/orlando_biblio/1', $this->linker->orlando('100'));
    }

    public function testOrlandoNotFound() : void {
        $this->assertNull($this->linker->orlando('800'));
    }

    public function testJacksonAnon() : void {
        $this->checker->method('hasRole')->willReturn(false);
        $this->assertStringEndsWith('details/abc', $this->linker->jackson('abc'));
    }

    public function testJacksonUser() : void {
        $this->assertStringEndsWith('resource/jackson/1', $this->linker->jackson('1234'));
    }

    public function testJacksonNotFound() : void {
        $this->assertStringEndsWith('details/abc123', $this->linker->jackson('abc123'));
    }

    public function testEnAnon() : void {
        $this->checker->method('hasRole')->willReturn(false);
        $this->assertNull($this->linker->en('abc'));
    }

    public function testEnUser() : void {
        $this->assertStringEndsWith('resource/en/1', $this->linker->en('en-0'));
    }

    public function testEnNotFound() : void {
        $this->assertNull($this->linker->en('abc-123456'));
    }

    public function testOsborneAnon() : void {
        $this->checker->method('hasRole')->willReturn(false);
        $this->assertNull($this->linker->osborne('abc'));
    }

    public function testOsborneUser() : void {
        $this->assertStringEndsWith('resource/osborne/1', $this->linker->osborne('abc-0'));
    }

    public function testOsborneNotFound() : void {
        $this->assertNull($this->linker->osborne('abc-123456'));
    }

    public function testEcco() : void {
        $this->assertStringContainsString('aa0aaaa', $this->linker->ecco('aaaaaa'));
    }

    /**
     * @dataProvider urlData
     *
     * @param mixed $expected
     * @param mixed $name
     * @param mixed $data
     */
    public function testUrl($expected, $name, $data) : void {
        $source = $this->createMock(Source::class);
        $source->method('getName')->willReturn($name);
        $actual = $this->linker->url($source, $data);
        if ($expected) {
            $this->assertMatchesRegularExpression($expected, $actual);
        } else {
            $this->assertNull($actual);
        }
    }

    public function urlData() {
        return [
            ['/^https\:\/\/example.com/', 'ESTC', 'https://example.com/foo/bar'],
            ['/^https\:\/\/example.com/', 'cheesery', 'https://example.com/foo/bar'],

            ['{resource/estc/1$}', 'ESTC', 'abc-0'],
            [null, 'ESTC', 'abcdef'],

            ['{resource/orlando_biblio/1$}', 'Orlando', '100'],
            [null, 'Orlando', 'abcdef'],

            ['{resource/jackson/1$}', 'Jackson Bibliography', '1234'],
            ['{details/abcdef$}', 'Jackson Bibliography', 'abcdef'],

            ['{resource/en/1$}', 'The English Novel 1770-1829', 'en-0'],
            [null, 'The English Novel 1770-1829', 'abcdef'],
            ['{resource/en/1$}', 'The English Novel 1830-1836', 'en-0'],
            [null, 'The English Novel 1830-1836', 'abcdef'],

            ['{resource/osborne/1$}', 'Osborne Collection of Early Children\'s Books', 'abc-0'],
            [null, 'Osborne Collection of Early Children\'s Books', 'abcdef'],

            ['{ab0cdef}', 'ECCO', 'abcdef'],

            //            ['/^https\:\/\/example.com/', 'ESTC', 'https://example.com/foo/bar'],
            //            ['/^https\:\/\/example.com/', 'ESTC', 'https://example.com/foo/bar'],
        ];
    }

    protected function setUp() : void {
        parent::setUp();
        $this->checker = $this->createMock(RoleChecker::class);
        $this->checker->method('hasRole')->willReturn(true);
        $this->linker = self::$container->get(SourceLinker::class);
        $this->linker->setRoleChecker($this->checker);
    }
}
