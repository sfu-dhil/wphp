<?php
/**
 * Created by PhpStorm.
 * User: mjoyce
 * Date: 2019-03-20
 * Time: 09:54
 */

namespace AppBundle\Tests\Services;


use AppBundle\Services\RoleChecker;
use Nines\UtilBundle\Tests\Util\BaseTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RoleCheckerTest extends BaseTestCase
{

    /**
     * @var RoleChecker
     */
    private $checker;

    public function testMissingToken() {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->will($this->throwException(new \Exception('No token')));
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn(false);
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertFalse($checker->hasRole('ROLE_USER'));
    }

    public function testNotAuthorized() {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(false);
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn('abc');
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertFalse($checker->hasRole('ROLE_USER'));
    }

    public function testAuthorized() {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(true);
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->method('getToken')->willReturn(true);
        $checker = new RoleChecker($authChecker, $storage);
        $this->assertTrue($checker->hasRole('ROLE_USER'));
    }
}
