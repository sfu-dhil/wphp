<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firm;
use AppBundle\Form\FirmSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Firm controller.
 *
 * @Route("/firm")
 */
class FirmController extends Controller
{

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
        $dql = 'SELECT e FROM AppBundle:Firm e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $firms = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'firms' => $firms,
        );
    }

    /**
     * Full text search for Firm entities.
     *
     * @Route("/quick_search", name="firm_quick_search")
     * @Method("GET")
     * @Template("AppBundle:Firm:search.html.twig")
     * @param Request $request
     * @return array
     */
    public function quickSearchAction(Request $request) {
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
        $firms = $paginator->paginate($query->execute(), $request->query->getint('page', 1), 25);
        return array(
            'search_form' => $form->createView(),
            'firms' => $firms,
        );
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

        if ($form->isValid()) {
            $data = array_filter($form->getData());
            if (count($data) > 2) {
                $repo = $em->getRepository(Firm::class);
                $query = $repo->buildSearchQuery($form->getData());
                $paginator = $this->get('knp_paginator');
                $firms = $paginator->paginate($query->execute(), $request->query->getint('page', 1), 25);
            } else {
                $this->addFlash('warning', 'You must enter a search term');
            }
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
            return $this->redirect($this->generateUrl('firm_index', array('id' => $q)));
        }
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
    public function showAction(Firm $firm) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Firm');
        return array(
            'firm' => $firm,
            'next' => $repo->next($firm),
            'previous' => $repo->previous($firm),
        );
    }
}
