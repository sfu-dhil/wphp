<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Firmrole;
use AppBundle\Form\FirmroleType;

/**
 * Firmrole controller.
 *
 * @Route("/firmrole")
 */
class FirmroleController extends Controller {

    /**
     * Lists all Firmrole entities.
     *
     * @Route("/", name="firmrole_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Firmrole e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $firmroles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'firmroles' => $firmroles,
        );
    }

    /**
     * Creates a new Firmrole entity.
     *
     * @Route("/new", name="firm_role_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        if (!$this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $firmrole = new Firmrole();
        $form = $this->createForm(FirmroleType::class, $firmrole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($firmrole);
            $em->flush();

            $this->addFlash('success', 'The new firmrole was created.');
            return $this->redirectToRoute('firm_role_show', array('id' => $firmrole->getId()));
        }

        return array(
            'firmrole' => $firmrole,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Firmrole entity.
     *
     * @Route("/{id}", name="firmrole_show")
     * @Method("GET")
     * @Template()
     * @param Firmrole $firmrole
     */
    public function showAction(Firmrole $firmrole) {

        return array(
            'firmrole' => $firmrole,
        );
    }

    /**
     * Displays a form to edit an existing Firmrole entity.
     *
     * @Route("/{id}/edit", name="firm_role_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Firmrole $firmrole
     */
    public function editAction(Request $request, Firmrole $firmrole) {
        if (!$this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm(FirmroleType::class, $firmrole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The firmrole has been updated.');
            return $this->redirectToRoute('firm_role_show', array('id' => $firmrole->getId()));
        }

        return array(
            'firmrole' => $firmrole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Firmrole entity.
     *
     * @Route("/{id}/delete", name="firm_role_delete")
     * @Method("GET")
     * @param Request $request
     * @param Firmrole $firmrole
     */
    public function deleteAction(Request $request, Firmrole $firmrole) {
        if (!$this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($firmrole);
        $em->flush();
        $this->addFlash('success', 'The firmrole was deleted.');

        return $this->redirectToRoute('firm_role_index');
    }

}
