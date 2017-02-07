<?php

namespace Nines\FeedbackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Nines\FeedbackBundle\Entity\CommentStatus;
use Nines\FeedbackBundle\Form\CommentStatusType;

/**
 * CommentStatus controller.
 *
 * @Route("/admin/comment_status")
 */
class CommentStatusController extends Controller
{
    /**
     * Lists all CommentStatus entities.
     *
     * @Route("/", name="admin_comment_status_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM FeedbackBundle:CommentStatus e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $commentStatuses = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'commentStatuses' => $commentStatuses,
        );
    }
    /**
     * Search for CommentStatus entities.
	 *
	 * To make this work, add a method like this one to the 
	 * FeedbackBundle:CommentStatus repository. Replace the fieldName with
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
     * @Route("/search", name="admin_comment_status_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('FeedbackBundle:CommentStatus');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$commentStatuses = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$commentStatuses = array();
		}

        return array(
            'commentStatuses' => $commentStatuses,
			'q' => $q,
        );
    }
    /**
     * Full text search for CommentStatus entities.
	 *
	 * To make this work, add a method like this one to the 
	 * FeedbackBundle:CommentStatus repository. Replace the fieldName with
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
	 * fulltext indexes on your CommentStatus entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="admin_comment_status_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('FeedbackBundle:CommentStatus');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$commentStatuses = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$commentStatuses = array();
		}

        return array(
            'commentStatuses' => $commentStatuses,
			'q' => $q,
        );
    }

    /**
     * Creates a new CommentStatus entity.
     *
     * @Route("/new", name="admin_comment_status_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $commentStatus = new CommentStatus();
        $form = $this->createForm('Nines\FeedbackBundle\Form\CommentStatusType', $commentStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentStatus);
            $em->flush();

            $this->addFlash('success', 'The new commentStatus was created.');
            return $this->redirectToRoute('admin_comment_status_show', array('id' => $commentStatus->getId()));
        }

        return array(
            'commentStatus' => $commentStatus,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a CommentStatus entity.
     *
     * @Route("/{id}", name="admin_comment_status_show")
     * @Method("GET")
     * @Template()
	 * @param CommentStatus $commentStatus
     */
    public function showAction(CommentStatus $commentStatus)
    {

        return array(
            'commentStatus' => $commentStatus,
        );
    }

    /**
     * Displays a form to edit an existing CommentStatus entity.
     *
     * @Route("/{id}/edit", name="admin_comment_status_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param CommentStatus $commentStatus
     */
    public function editAction(Request $request, CommentStatus $commentStatus)
    {
        $editForm = $this->createForm('Nines\FeedbackBundle\Form\CommentStatusType', $commentStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The commentStatus has been updated.');
            return $this->redirectToRoute('admin_comment_status_show', array('id' => $commentStatus->getId()));
        }

        return array(
            'commentStatus' => $commentStatus,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a CommentStatus entity.
     *
     * @Route("/{id}/delete", name="admin_comment_status_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param CommentStatus $commentStatus
     */
    public function deleteAction(Request $request, CommentStatus $commentStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentStatus);
        $em->flush();
        $this->addFlash('success', 'The commentStatus was deleted.');

        return $this->redirectToRoute('admin_comment_status_index');
    }
}
