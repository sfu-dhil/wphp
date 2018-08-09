<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EstcMarc;
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
 * EstcMarc controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/estc")
 */
class EstcMarcController extends Controller {

    /**
     * Lists all EstcMarc entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_estc_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, MarcManager $manager) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(EstcMarc::class);
        $query = $repo->indexQuery();
        $paginator = $this->get('knp_paginator');
        $estcMarcs = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'estcMarcs' => $estcMarcs,
            'manager' => $manager,
        );
    }

    /**
     * Typeahead API endpoint for EstcMarc entities.
     *
     * To make this work, add something like this to EstcMarcRepository:
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
     * @Route("/typeahead", name="resource_estc_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(EstcMarc::class);
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
     * Search for EstcMarc entities.
     *
     * @param Request $request
     *
     * @Route("/search", name="resource_estc_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request, MarcManager $manager) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:EstcMarc');
        $q = $request->query->get('q');
        if ($q) {
            $result = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $titleIds = $paginator->paginate($result, $request->query->getInt('page', 1), 25);
        } else {
            $titleIds = array();
        }
        $estcMarcs = array();
        foreach($titleIds as $titleId) {
            $estcMarcs[] = $repo->findOneBy(array(
                'titleId' => $titleId,
                'field' => 'ldr',
            ));
        }
        return array(
            'titleIds' => $titleIds,
            'estcMarcs' => $estcMarcs,
            'q' => $q,
            'manager' => $manager,
        );
    }

    /**
     * Finds and displays a EstcMarc entity.
     *
     * @param EstcMarc $estcMarc
     *
     * @return array
     *
     * @Route("/{id}", name="resource_estc_show")
     * @Method("GET")
     * @ParamConverter("estcMarc", options={"mapping": {"id": "titleId"}})
     * @Template()
     */
    public function showAction(EstcMarc $estcMarc, MarcManager $manager) {

        return array(
            'estcMarc' => $estcMarc,
            'manager' => $manager,
        );
    }

}
