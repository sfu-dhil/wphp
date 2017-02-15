<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firm;
use Nines\FeedbackBundle\Entity\Comment;
use Nines\FeedbackBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * Search for Title entities.
     *
     * @Route("/jump", name="firm_jump")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function jumpAction(Request $request)
    {
		$q = $request->query->get('q');
		if($q) {
            return $this->redirect($this->generateUrl('firm_show', array('id' => $q)));
		} else {
            return $this->redirect($this->generateUrl('firm_index', array('id' => $q)));
		}
    }
    
    /**
     * Finds and displays a Firm entity.
     *
     * @Route("/{id}", name="firm_show")
     * @Method({"GET","POST"})
     * @Template()
	 * @param Firm $firm
     */
    public function showAction(Request $request, Firm $firm)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Firm');        
		return array(
            'firm' => $firm,
            'next' => $repo->next($firm),
            'previous' => $repo->previous($firm),
        );
    }
}
