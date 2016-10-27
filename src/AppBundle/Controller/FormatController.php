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
    public function indexAction(Request $request)
    {
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
     * Search for Format entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Format repository. Replace the fieldName with
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
     * @Route("/search", name="format_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Format');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$formats = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$formats = array();
		}

        return array(
            'formats' => $formats,
			'q' => $q,
        );
    }
    /**
     * Full text search for Format entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Format repository. Replace the fieldName with
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
	 * fulltext indexes on your Format entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="format_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Format');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$formats = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$formats = array();
		}

        return array(
            'formats' => $formats,
			'q' => $q,
        );
    }

    /**
     * Creates a new Format entity.
     *
     * @Route("/new", name="format_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $format = new Format();
        $form = $this->createForm('AppBundle\Form\FormatType', $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($format);
            $em->flush();

            $this->addFlash('success', 'The new format was created.');
            return $this->redirectToRoute('format_show', array('id' => $format->getId()));
        }

        return array(
            'format' => $format,
            'form' => $form->createView(),
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
    public function showAction(Format $format)
    {

        return array(
            'format' => $format,
        );
    }

    /**
     * Displays a form to edit an existing Format entity.
     *
     * @Route("/{id}/edit", name="format_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Format $format
     */
    public function editAction(Request $request, Format $format)
    {
        $editForm = $this->createForm('AppBundle\Form\FormatType', $format);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The format has been updated.');
            return $this->redirectToRoute('format_show', array('id' => $format->getId()));
        }

        return array(
            'format' => $format,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Format entity.
     *
     * @Route("/{id}/delete", name="format_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Format $format
     */
    public function deleteAction(Request $request, Format $format)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($format);
        $em->flush();
        $this->addFlash('success', 'The format was deleted.');

        return $this->redirectToRoute('format_index');
    }
}
