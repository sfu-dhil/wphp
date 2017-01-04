<?php

namespace FeedbackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FeedbackBundle\Entity\CommentNote;
use FeedbackBundle\Form\CommentNoteType;

/**
 * CommentNote controller.
 *
 * @Route("/admin/comment_note")
 */
class CommentNoteController extends Controller
{
    /**
     * Lists all CommentNote entities.
     *
     * @Route("/", name="admin_comment_note_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM FeedbackBundle:CommentNote e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $commentNotes = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'commentNotes' => $commentNotes,
        );
    }

    /**
     * Full text search for CommentNote entities.
     *
     * @Route("/fulltext", name="admin_comment_note_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('FeedbackBundle:CommentNote');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$commentNotes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$commentNotes = array();
		}

        return array(
            'commentNotes' => $commentNotes,
			'q' => $q,
        );
    }

    /**
     * Creates a new CommentNote entity.
     *
     * @Route("/new", name="admin_comment_note_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $commentNote = new CommentNote();
        $form = $this->createForm('FeedbackBundle\Form\CommentNoteType', $commentNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentNote);
            $em->flush();

            $this->addFlash('success', 'The new commentNote was created.');
            return $this->redirectToRoute('admin_comment_note_show', array('id' => $commentNote->getId()));
        }

        return array(
            'commentNote' => $commentNote,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a CommentNote entity.
     *
     * @Route("/{id}", name="admin_comment_note_show")
     * @Method("GET")
     * @Template()
	 * @param CommentNote $commentNote
     */
    public function showAction(CommentNote $commentNote)
    {

        return array(
            'commentNote' => $commentNote,
        );
    }
}
