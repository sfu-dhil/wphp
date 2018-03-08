<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firmrole;
use AppBundle\Form\FirmroleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @Route("/typeahead", name="firmrole_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request) {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Firmrole::class);
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getName(),
            ];
        }
        
        return new JsonResponse($data);
    }
    
    /**
     * Creates a new Firmrole entity.
     *
     * @Route("/new", name="firmrole_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $firmrole = new Firmrole();
        $form = $this->createForm(FirmroleType::class, $firmrole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($firmrole);
            $em->flush();

            $this->addFlash('success', 'The new firmrole was created.');
            return $this->redirectToRoute('firmrole_show', array('id' => $firmrole->getId()));
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
     * @Route("/{id}/edit", name="firmrole_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firmrole $firmrole
     */
    public function editAction(Request $request, Firmrole $firmrole) {
        $editForm = $this->createForm(FirmroleType::class, $firmrole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The firmrole has been updated.');
            return $this->redirectToRoute('firmrole_show', array('id' => $firmrole->getId()));
        }

        return array(
            'firmrole' => $firmrole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Firmrole entity.
     *
     * @Route("/{id}/delete", name="firmrole_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firmrole $firmrole
     */
    public function deleteAction(Request $request, Firmrole $firmrole) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($firmrole);
        $em->flush();
        $this->addFlash('success', 'The firmrole was deleted.');

        return $this->redirectToRoute('firmrole_index');
    }

}
