<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Services;

use App\Services\RoleChecker;
use Exception;
use Nines\UtilBundle\Tests\ControllerBaseCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RoleCheckerTest extends ControllerBaseCase
{
    /**
     * @var RoleChecker
     */
    private $checker;

    public function testMissingToken() : void {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->will($this->throwException(new Exception('No token')));
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn(false);
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertFalse($checker->hasRole('ROLE_USER'));
    }

    public function testNotAuthorized() : void {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(false);
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn('abc');
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertFalse($checker->hasRole('ROLE_USER'));
    }

    public function testAuthorized() : void {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(true);
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn(true);
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertTrue($checker->hasRole('ROLE_USER'));
    }
}
