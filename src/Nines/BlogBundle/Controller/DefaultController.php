<?php

namespace Nines\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NinesBlogBundle:Default:index.html.twig');
    }
}
