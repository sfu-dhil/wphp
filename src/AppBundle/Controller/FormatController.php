<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Format;
use AppBundle\Form\FormatType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Format controller.
 *
 * @Route("/format")
 */
class FormatController extends Controller {

    /**
     * Lists all Format entities.
     *
     * @Route("/", name="format_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
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
     * Creates a new Format entity.
     *
     * @Route("/new", name="format_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $format = new Format();
        $form = $this->createForm(FormatType::class, $format);
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
    public function showAction(Request $request, Format $format) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT t FROM AppBundle:Title t WHERE t.format = :format ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('format', $format);
        $paginator = $this->get('knp_paginator');
        $titles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'format' => $format,
            'titles' => $titles,
        );
    }

    /**
     * Displays a form to edit an existing Format entity.
     *
     * @Route("/{id}/edit", name="format_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     * @param Format $format
     */
    public function editAction(Request $request, Format $format) {
        $editForm = $this->createForm(FormatType::class, $format);
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
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Format $format
     */
    public function deleteAction(Request $request, Format $format) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($format);
        $em->flush();
        $this->addFlash('success', 'The format was deleted.');

        return $this->redirectToRoute('format_index');
    }
}
