<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Feedback;
use AppBundle\Form\FeedbackType;

/**
 * Feedback controller.
 *
 * @Route("/feedback")
 */
class FeedbackController extends Controller
{
    /**
     * Lists all Feedback entities.
     *
     * @Route("/", name="feedback_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        if(! $this->isGranted('ROLE_ADMIN')) {
          $this->addFlash('danger', "You must login to access this page.");
          return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Feedback e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $feedbacks = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'feedbacks' => $feedbacks,
        );
    }
    
    /**
     * Creates a new Feedback entity.
     *
     * @Route("/new", name="feedback_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        if($this->isGranted('ROLE_USER')) {
          $this->addFlash('danger', "You cannot be logged in to access this page.");
          return $this->redirect($this->generateUrl('homepage'));
        }
      
        $feedback = new Feedback();
        $form = $this->createForm('AppBundle\Form\FeedbackType', $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', 'The new feedback was created.');
            return $this->redirectToRoute('homepage');
        }

        return array(
            'feedback' => $feedback,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Feedback entity.
     *
     * @Route("/{id}", name="feedback_show")
     * @Method("GET")
     * @Template()
	 * @param Feedback $feedback
     */
    public function showAction(Feedback $feedback)
    {
        if(! $this->isGranted('ROLE_ADMIN')) {
          $this->addFlash('danger', "You must login to access this page.");
          return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        return array(
            'feedback' => $feedback,
        );
    }

}
