<?php

namespace FeedbackBundle\Controller;

use FeedbackBundle\Entity\Comment;
use FeedbackBundle\Form\StatusType;
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
     * Search for Comment entities.
     *
     * To make this work, add a method like this one to the 
     * FeedbackBundle:Comment repository. Replace the fieldName with
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
     * @Route("/search", name="admin_comment_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('FeedbackBundle:Comment');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
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
     * Full text search for Comment entities.
     *
     * To make this work, add a method like this one to the 
     * FeedbackBundle:Comment repository. Replace the fieldName with
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
     * fulltext indexes on your Comment entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
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
        return array(
            'comment' => $comment,
            'service' => $service,
            'statuses' => $em->getRepository('FeedbackBundle:CommentStatus')->findAll(),
            'statusForm' => $statusForm->createView(),
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
