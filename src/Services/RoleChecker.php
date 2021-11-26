<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Service to check the current user's roles.
 */
class RoleChecker {
    private AuthorizationCheckerInterface $authChecker;

    private TokenStorageInterface $tokenStorage;

    /**
     * RoleChecker constructor.
     */
    public function __construct(AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage) {
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Check that the current use has the given role.
     */
    public function hasRole(string $role) : bool {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }
}
