<?php

namespace AppBundle\Services;

use CKSource\Bundle\CKFinderBundle\Authentication\Authentication;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * CKFinder authentication service.
 */
class CKFinderAuthenticator extends Authentication
{

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    private function hasRole($role)
    {
        if (!$this->authChecker) {
            $this->authChecker = $this->container->get('security.authorization_checker');
        }
        if( ! $this->tokenStorage) {
            $this->tokenStorage = $this->container->get('security.token_storage');
        }
        if (!$this->tokenStorage->getToken()) {
            return false;
        }
        return $this->authChecker->isGranted($role);
    }

    public function authenticate()
    {
        return $this->hasRole('ROLE_BLOG_ADMIN');
    }

}
