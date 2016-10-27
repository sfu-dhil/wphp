<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Title;
use AppBundle\Form\TitleType;

/**
 * Title controller.
 *
 * @Route("/title")
 */
class TitleController extends Controller
{
    /**
     * Lists all Title entities.
     *
     * @Route("/", name="title_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Title e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $titles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }
    /**
     * Search for Title entities.
     *
     * @Route("/search", name="title_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Title');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$titles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$titles = array();
		}

        return array(
            'titles' => $titles,
			'q' => $q,
        );
    }
    /**
     * Full text search for Title entities.
     *
     * @Route("/fulltext", name="title_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Title');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$titles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$titles = array();
		}

        return array(
            'titles' => $titles,
			'q' => $q,
        );
    }
	
    /**
     * Finds and displays a Title entity.
     *
     * @Route("/{id}", name="title_show")
     * @Method("GET")
     * @Template()
	 * @param Title $title
     */
    public function showAction(Title $title)
    {

        return array(
            'title' => $title,
        );
    }

}
