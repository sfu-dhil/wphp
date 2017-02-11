<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use Nines\FeedbackBundle\Entity\Comment;
use Nines\FeedbackBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends Controller
{
    /**
     * Lists all Person entities.
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Person e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $people = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'people' => $people,
        );
    }
	
    /**
     * Full text search for Person entities.
     *
     * @Route("/fulltext", name="person_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Person');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$people = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$people = array();
		}

        return array(
            'people' => $people,
			'q' => $q,
        );
    }

    /**
     * Search for Title entities.
     *
     * @Route("/jump", name="person_jump")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function jumpAction(Request $request)
    {
		$q = $request->query->get('q');
		if($q) {
            return $this->redirect($this->generateUrl('person_show', array('id' => $q)));
		} else {
            return $this->redirect($this->generateUrl('person_index', array('id' => $q)));
		}
    }
    
    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}", name="person_show")
     * @Method({"GET","POST"})
     * @Template()
	 * @param Person $person
     */
    public function showAction(Request $request, Person $person)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
		$service = $this->get('feedback.comment');
        if ($form->isSubmitted() && $form->isValid()) {
            $service->addComment($person, $comment);
            $this->addFlash('success', 'Thank you for your suggestion.');
            return $this->redirect($this->generateUrl('person_show', array('id' => $person->getId())));
        }

        $comments = $service->findComments($person);
		return array(
            'form' => $form->createView(),
            'person' => $person,
			'comments' => $comments,
			'service' => $service,
        );

    }

}
