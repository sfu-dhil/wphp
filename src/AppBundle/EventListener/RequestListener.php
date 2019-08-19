<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RequestListener {

    static $loginUrls = array(
        '/login',
        '/register',
        '/resetting',
        '/feedback',
    );

    private $allowLogins;

    private $generator;

    private $session;

    public function __construct($allowLogins, UrlGeneratorInterface $generator, SessionInterface $session) {
        $this->allowLogins = $allowLogins;
        $this->generator = $generator;
        $this->session = $session;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        if($this->allowLogins || ! $event->isMasterRequest()) {
            return;
        }
        if(in_array($event->getRequest()->getPathInfo(), self::$loginUrls)) {
            $this->session->getFlashBag()->add('danger', 'Access to this resource is temporarily restricted.');
            $response = new RedirectResponse($this->generator->generate('homepage'));
            $event->setResponse($response);
        }
    }

}
