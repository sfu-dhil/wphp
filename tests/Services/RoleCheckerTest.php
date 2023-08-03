<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Services\RoleChecker;
use Exception;
use Nines\UtilBundle\TestCase\ControllerTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RoleCheckerTest extends ControllerTestCase {
    public function testMissingToken() : void {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->will($this->throwException(new Exception('No token')));
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn(null);
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertFalse($checker->hasRole('ROLE_USER'));
    }

    public function testNotAuthorized() : void {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(false);
        $token = $this->createMock(TokenInterface::class);
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn($token);
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertFalse($checker->hasRole('ROLE_USER'));
    }

    public function testAuthorized() : void {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(true);
        $token = $this->createMock(TokenInterface::class);
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn($token);
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertTrue($checker->hasRole('ROLE_USER'));
    }
}
