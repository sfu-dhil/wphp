<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Geonames;
use AppBundle\Form\GeonamesType;

/**
 * Geonames controller.
 *
 * @Route("/geonames")
 */
class GeonamesController extends Controller
{
    /**
     * Lists all Geonames entities.
     *
     * @Route("/", name="geonames_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Geonames e ORDER BY e.geonameid';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $geonames = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'geonames' => $geonames,
        );
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}", name="geonames_show")
     * @Method("GET")
     * @Template()
     * @param Geonames $geoname
     */
    public function showAction(Geonames $geoname) {

        return array(
            'geoname' => $geoname,
        );
    }
}
