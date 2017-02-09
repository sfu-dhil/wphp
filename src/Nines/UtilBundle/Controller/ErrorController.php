<?php

namespace Nines\UtilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends Controller
{
    /**
     * @param FlattenException $exception
     * @param DebugLoggerInterface $logger
     */
    public function showExceptionAction(FlattenException $exception, DebugLoggerInterface $logger) {
        $response = new Response();
        $response->setStatusCode($exception->getStatusCode());
        $response->headers->set('Content-Type', 'text/html');
        
        $env = $this->container->get( 'kernel' )->getEnvironment();
        switch($env) {
            case 'prod':
                $response->setContent($this->render('UtilBundle:Error:prod.html.twig', array(
                    'exception' => $exception,
                )));
                break;
            case 'dev':
                $response->setContent($this->render('UtilBundle:Error:dev.html.twig', array(
                    'exception' => $exception,
                )));
                break;
            case 'test':
                $response->headers->set('Content-Type', 'text/plain');
                $response->setContent($exception->getMessage());
                break;
            default:
                break;
        }
        return $response;
    }
}
