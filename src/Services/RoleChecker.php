<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Service to check the current user's roles.
 */
class RoleChecker {
    /**
     * RoleChecker constructor.
     */
    public function __construct(private AuthorizationCheckerInterface $authChecker, private TokenStorageInterface $tokenStorage) {
    }

    /**
     * Check that the current use has the given role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }
}
