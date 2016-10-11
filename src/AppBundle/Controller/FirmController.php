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
	 * To make this work, add a method like this one to the 
	 * AppBundle:Firm repository. Replace the fieldName with
	 * something appropriate, and adjust the generated search.html.twig
	 * template.
	 * 
     //    public function searchQuery($q) {
     //        $qb = $this->createQueryBuilder('e');
     //        $qb->where("e.fieldName like '%$q%'");
     //        return $qb->getQuery();
     //    }
	 *
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
	 * To make this work, add a method like this one to the 
	 * AppBundle:Firm repository. Replace the fieldName with
	 * something appropriate, and adjust the generated fulltext.html.twig
	 * template.
	 * 
	//    public function fulltextQuery($q) {
	//        $qb = $this->createQueryBuilder('e');
	//        $qb->addSelect("MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') as score");
	//        $qb->add('where', "MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') > 0.5");
	//        $qb->orderBy('score', 'desc');
	//        $qb->setParameter('q', $q);
	//        return $qb->getQuery();
	//    }	 
	 * 
	 * Requires a MatchAgainst function be added to doctrine, and appropriate
	 * fulltext indexes on your Firm entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
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
     * Creates a new Firm entity.
     *
     * @Route("/new", name="firm_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $firm = new Firm();
        $form = $this->createForm('AppBundle\Form\FirmType', $firm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($firm);
            $em->flush();

            $this->addFlash('success', 'The new firm was created.');
            return $this->redirectToRoute('firm_show', array('id' => $firm->getId()));
        }

        return array(
            'firm' => $firm,
            'form' => $form->createView(),
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

    /**
     * Displays a form to edit an existing Firm entity.
     *
     * @Route("/{id}/edit", name="firm_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Firm $firm
     */
    public function editAction(Request $request, Firm $firm)
    {
        $editForm = $this->createForm('AppBundle\Form\FirmType', $firm);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The firm has been updated.');
            return $this->redirectToRoute('firm_show', array('id' => $firm->getId()));
        }

        return array(
            'firm' => $firm,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Firm entity.
     *
     * @Route("/{id}/delete", name="firm_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Firm $firm
     */
    public function deleteAction(Request $request, Firm $firm)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($firm);
        $em->flush();
        $this->addFlash('success', 'The firm was deleted.');

        return $this->redirectToRoute('firm_index');
    }
}
