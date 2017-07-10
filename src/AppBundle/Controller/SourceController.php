<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Source;

/**
 * Source controller.
 *
 * @Route("/source")
 */
class SourceController extends Controller
{
    /**
     * Lists all Source entities.
     *
     * @Route("/", name="source_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Source e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $sources = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'sources' => $sources,
        );
    }

    /**
     * Finds and displays a Source entity.
     *
     * @Route("/{id}", name="source_show")
     * @Method("GET")
     * @Template()
     * @param Source $source
     */
    public function showAction(Source $source) {

        return array(
            'source' => $source,
        );
    }
}
