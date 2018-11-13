<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firm;
use AppBundle\Form\Firm\FirmSearchType;
use AppBundle\Form\Firm\FirmType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Firm controller.
 *
 * @Route("/firm")
 */
class FirmController extends Controller {

    /**
     * Lists all Firm entities.
     *
     * @Route("/", name="firm_index")
     * @Method("GET")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(FirmSearchType::class, null, array(
            'action' => $this->generateUrl('firm_search'),
            'entity_manager' => $em
        ));
        $q = $request->query->get('q');
        $form->get('name')->submit($q);
        $repo = $em->getRepository(Firm::class);
        $query = $repo->buildSearchQuery(array('name' => $q));
        $paginator = $this->get('knp_paginator');
        $firms = $paginator->paginate($query, $request->query->getint('page', 1), 25);
        return array(
            'search_form' => $form->createView(),
            'firms' => $firms,
            'repo' => $em->getRepository(Role::class),
        );
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="firm_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request) {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Firm::class);
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
     * Full text search for Firm entities.
     *
     * @Route("/search", name="firm_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(FirmSearchType::class, null, array('entity_manager' => $em));
        $form->handleRequest($request);
        $firms = array();

        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $em->getRepository(Firm::class);
            $query = $repo->buildSearchQuery($form->getData());
            $paginator = $this->get('knp_paginator');
            $firms = $paginator->paginate($query, $request->query->getint('page', 1), 25);
        }
        return array(
            'search_form' => $form->createView(),
            'firms' => $firms,
        );
    }

    /**
     * Search for Title entities.
     *
     * @Route("/jump", name="firm_jump")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function jumpAction(Request $request) {
        $q = $request->query->get('q');
        if ($q) {
            return $this->redirect($this->generateUrl('firm_show', array('id' => $q)));
        } else {
            return $this->redirect($this->generateUrl('firm_index'));
        }
    }

    /**
     * Creates a new Firm entity.
     *
     * @Route("/new", name="firm_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     */
    public function newAction(Request $request) {
        $firm = new Firm();
        $form = $this->createForm(FirmType::class, $firm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($firm);
            $em->flush();

            $this->addFlash('success', 'The new firm was created.');
            return $this->redirectToRoute('firm_show', array('id' => $firm->getId()));
        }

        return array(
            'firm' => $firm,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Firm entity.
     *
     * @Route("/{id}.{_format}", name="firm_show", defaults={"_format": "html"})
     * @Method({"GET","POST"})
     * @Template()
     * @param Firm $firm
     * @return array
     */
    public function showAction(Request $request, Firm $firm) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Firm');
        $paginator = $this->get('knp_paginator');
        $firmRoles = $firm->getTitleFirmroles(true);
        $pagination = $paginator->paginate($firmRoles, $request->query->getint('page', 1), 25);
        return array(
            'firm' => $firm,
            'next' => $repo->next($firm),
            'previous' => $repo->previous($firm),
            'pagination' => $pagination,
        );
    }

    /**
     * Displays a form to edit an existing Firm entity.
     *
     * @Route("/{id}/edit", name="firm_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firm $firm
     */
    public function editAction(Request $request, Firm $firm) {
        $editForm = $this->createForm(FirmType::class, $firm);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The firm has been updated.');
            return $this->redirectToRoute('firm_show', array('id' => $firm->getId()));
        }

        return array(
            'firm' => $firm,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Firm entity.
     *
     * @Route("/{id}/delete", name="firm_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @param Request $request
     * @param Firm $firm
     */
    public function deleteAction(Request $request, Firm $firm) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($firm);
        $em->flush();
        $this->addFlash('success', 'The firm was deleted.');

        return $this->redirectToRoute('firm_index');
    }

}
