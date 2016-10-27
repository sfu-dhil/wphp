<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Firm;
use AppBundle\Form\FirmType;

/**
 * Firm controller.
 *
 * @Route("/firm")
 */
class FirmController extends Controller
{
    /**
     * Lists all Firm entities.
     *
     * @Route("/", name="firm_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Firm e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $firms = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'firms' => $firms,
        );
    }
    /**
     * Search for Firm entities.
	 *
     * @Route("/search", name="firm_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Firm');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$firms = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$firms = array();
		}

        return array(
            'firms' => $firms,
			'q' => $q,
        );
    }
    /**
     * Full text search for Firm entities.
	 *
     * @Route("/fulltext", name="firm_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Firm');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$firms = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$firms = array();
		}

        return array(
            'firms' => $firms,
			'q' => $q,
        );
    }

    /**
     * Finds and displays a Firm entity.
     *
     * @Route("/{id}", name="firm_show")
     * @Method("GET")
     * @Template()
	 * @param Firm $firm
     */
    public function showAction(Firm $firm)
    {

        return array(
            'firm' => $firm,
        );
    }
}
