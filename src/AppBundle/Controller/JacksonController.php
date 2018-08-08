<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Jackson;

/**
 * Jackson controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/jackson")
 */
class JacksonController extends Controller {

    /**
     * Lists all Jackson entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_jackson_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Jackson::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $jacksons = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'jacksons' => $jacksons,
        );
    }

    /**
     * Typeahead API endpoint for Jackson entities.
     *
     * To make this work, add something like this to JacksonRepository:
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
     * @Route("/typeahead", name="resource_jackson_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Jackson::class);
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
     * Search for Jackson entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:Jackson repository. Replace the fieldName with
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
     * @Route("/search", name="resource_jackson_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Jackson');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $jacksons = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $jacksons = array();
        }

        return array(
            'jacksons' => $jacksons,
            'q' => $q,
        );
    }

    /**
     * Finds and displays a Jackson entity.
     *
     * @param Jackson $jackson
     *
     * @return array
     *
     * @Route("/{id}", name="resource_jackson_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Jackson $jackson) {

        return array(
            'jackson' => $jackson,
        );
    }

}
