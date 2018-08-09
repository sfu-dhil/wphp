<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\En;

/**
 * En controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/en")
 */
class EnController extends Controller {

    /**
     * Lists all En entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_en_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(En::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $ens = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'ens' => $ens,
        );
    }

    /**
     * Typeahead API endpoint for En entities.
     *
     * To make this work, add something like this to EnRepository:
      //    public function typeaheadQuery($q) {
      //        $qb = $this->createQueryBuilder('e');
      //        $qb->andWhere("e.name LIKE :q");
      //        $qb->orderBy('e.name');
      //        $qb->setParameter('q', "{$q}%");
      //        return $qb->getQuery()->execute();
      //    }
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="resource_en_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(En::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }
        return new JsonResponse($data);
    }

    /**
     * Search for En entities.
     *
     * @param Request $request
     *
     * @Route("/search", name="resource_en_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:En');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $ens = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $ens = array();
        }

        return array(
            'ens' => $ens,
            'q' => $q,
        );
    }

    /**
     * Finds and displays a En entity.
     *
     * @param En $en
     *
     * @return array
     *
     * @Route("/{id}", name="resource_en_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(En $en) {

        return array(
            'en' => $en,
        );
    }

}
