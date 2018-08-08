<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OsborneMarc;
use AppBundle\Services\MarcManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * OsborneMarc controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/osborne")
 */
class OsborneMarcController extends Controller {

    /**
     * Lists all OsborneMarc entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_osborne_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, MarcManager $manager) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(OsborneMarc::class);
        $query = $repo->indexQuery();
        $paginator = $this->get('knp_paginator');
        $osborneMarcs = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'osborneMarcs' => $osborneMarcs,
            'manager' => $manager,
        );
    }

    /**
     * Typeahead API endpoint for OsborneMarc entities.
     *
     * To make this work, add something like this to OsborneMarcRepository:
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
     * @Route("/typeahead", name="resource_osborne_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(OsborneMarc::class);
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
     * Search for OsborneMarc entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:OsborneMarc repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     *
     * <code><pre>
     *    public function searchQuery($q) {
     *       $qb = $this->createQueryBuilder('e');
     *       $qb->addSelect("MATCH (e.title) AGAINST(:q BOOLEAN) as HIDDEN score");
     *       $qb->orderBy('score', 'DESC');
     *       $qb->setParameter('q', $q);
     *       return $qb->getQuery();
     *    }
     * </pre></code>
     *
     * @param Request $request
     *
     * @Route("/search", name="resource_osborne_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:OsborneMarc');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $osborneMarcs = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $osborneMarcs = array();
        }

        return array(
            'osborneMarcs' => $osborneMarcs,
            'q' => $q,
        );
    }

    /**
     * Finds and displays a OsborneMarc entity.
     *
     * @param OsborneMarc $osborneMarc
     *
     * @return array
     *
     * @Route("/{id}", name="resource_osborne_show")
     * @Method("GET")
     * @ParamConverter("osborneMarc", options={"mapping": {"id": "titleId"}})
     * @Template()
     */
    public function showAction(OsborneMarc $osborneMarc, MarcManager $manager) {

        return array(
            'osborneMarc' => $osborneMarc,
            'manager' => $manager
        );
    }

}
