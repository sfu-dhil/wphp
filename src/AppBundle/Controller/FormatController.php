<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Format;
use AppBundle\Form\FormatType;

/**
 * Format controller.
 *
 * @Route("/format")
 */
class FormatController extends Controller
{
    /**
     * Lists all Format entities.
     *
     * @Route("/", name="format_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Format e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $formats = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'formats' => $formats,
        );
    }

    /**
     * Finds and displays a Format entity.
     *
     * @Route("/{id}", name="format_show")
     * @Method("GET")
     * @Template()
     * @param Format $format
     */
    public function showAction(Format $format) {

        return array(
            'format' => $format,
        );
    }
}
