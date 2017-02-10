<?php

namespace Nines\FeedbackBundle\Controller;

use Nines\FeedbackBundle\Entity\Comment;
use Nines\FeedbackBundle\Entity\CommentNote;
use Nines\FeedbackBundle\Form\CommentNoteType;
use Nines\FeedbackBundle\Form\StatusType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 *
 * @Route("/admin/comment")
 */
class CommentController extends Controller {

    /**
     * Lists all Comment entities.
     *
     * @Route("/", name="admin_comment_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $statusRepo = $em->getRepository('FeedbackBundle:CommentStatus');
        $commentRepo = $em->getRepository('FeedbackBundle:Comment');
        $qb = $commentRepo->createQueryBuilder('e');

        $statusName = $request->query->get('status');
        if ($statusName) {
            $status = $statusRepo->findOneBy(array(
                'name' => $statusName,
            ));
            $qb->andWhere('e.status = :status');
            $qb->setParameter('status', $status);
        }
        $qb->orderBy('e.id');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $comments = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        $service = $this->get('feedback.comment');

        return array(
            'comments' => $comments,
            'service' => $service,
            'statuses' => $statusRepo->findAll(),
        );
    }

    /**
     * Full text search for Comment entities.
     *
     * @Route("/fulltext", name="admin_comment_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('FeedbackBundle:Comment');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $comments = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $comments = array();
        }

        return array(
            'comments' => $comments,
            'q' => $q,
        );
    }

    /**
     * Finds and displays a Comment entity.
     *
     * @Route("/{id}", name="admin_comment_show")
     * @Method({"GET","POST"})
     * @Template()
     * @param Comment $comment
     */
    public function showAction(Request $request, Comment $comment) {
        $em = $this->getDoctrine()->getManager();
        $service = $this->get('feedback.comment');
        $statusForm = $this->createForm(StatusType::class, $comment);
        $statusForm->handleRequest($request);
        if ($statusForm->isSubmitted() && $statusForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The comment was updated.');
            return $this->redirect($this->generateUrl('admin_comment_show', array(
                'id' => $comment->getId()
            )));
        }
        
        $commentNote = new CommentNote();
        $commentNote->setComment($comment);
        // $this->getUser() is depreciated in Symfony 3.2, and should be replaced
        // with a typehinted parameter.
        // http://symfony.com/blog/new-in-symfony-3-2-user-value-resolver-for-controllers
        $commentNote->setUser($this->getUser()); 
        $noteForm = $this->createForm(CommentNoteType::class, $commentNote);
        $noteForm->handleRequest($request);
        if($noteForm->isSubmitted() && $noteForm->isValid()) {
            $comment->addNote($commentNote);
            $em->persist($commentNote);
            $em->flush();
            $this->addFlash('success', 'The comment note was added.');
            return $this->redirect($this->generateUrl('admin_comment_show', array(
                'id' => $comment->getId()
            )));
        }
        
        return array(
            'comment' => $comment,
            'service' => $service,
            'statuses' => $em->getRepository('FeedbackBundle:CommentStatus')->findAll(),
            'statusForm' => $statusForm->createView(),
            'noteForm' => $noteForm->createView(),
        );
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{id}/delete", name="admin_comment_delete")
     * @Method("GET")
     * @param Request $request
     * @param Comment $comment
     */
    public function deleteAction(Request $request, Comment $comment) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', 'The comment was deleted.');

        if ($request->query->has('ref')) {
            return $this->redirect($request->query->get('ref'));
        }

        return $this->redirectToRoute('admin_comment_index');
    }

}