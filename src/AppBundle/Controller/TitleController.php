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
	 * To make this work, add a method like this one to the 
	 * AppBundle:Title repository. Replace the fieldName with
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
	 * To make this work, add a method like this one to the 
	 * AppBundle:Title repository. Replace the fieldName with
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
	 * fulltext indexes on your Title entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
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
     * Creates a new Title entity.
     *
     * @Route("/new", name="title_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $title = new Title();
        $form = $this->createForm('AppBundle\Form\TitleType', $title);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($title);
            $em->flush();

            $this->addFlash('success', 'The new title was created.');
            return $this->redirectToRoute('title_show', array('id' => $title->getId()));
        }

        return array(
            'title' => $title,
            'form' => $form->createView(),
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

    /**
     * Displays a form to edit an existing Title entity.
     *
     * @Route("/{id}/edit", name="title_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Title $title
     */
    public function editAction(Request $request, Title $title)
    {
        $editForm = $this->createForm('AppBundle\Form\TitleType', $title);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The title has been updated.');
            return $this->redirectToRoute('title_show', array('id' => $title->getId()));
        }

        return array(
            'title' => $title,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Title entity.
     *
     * @Route("/{id}/delete", name="title_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Title $title
     */
    public function deleteAction(Request $request, Title $title)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($title);
        $em->flush();
        $this->addFlash('success', 'The title was deleted.');

        return $this->redirectToRoute('title_index');
    }
}
