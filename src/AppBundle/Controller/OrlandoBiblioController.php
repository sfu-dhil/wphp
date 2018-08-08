<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrlandoBiblio;
use AppBundle\Services\OrlandoManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * OrlandoBiblio controller.
 *
 * @Security("has_role('ROLE_USER')")
 * @Route("/resource/orlando_biblio")
 */
class OrlandoBiblioController extends Controller {

    /**
     * Lists all OrlandoBiblio entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="resource_orlando_biblio_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, OrlandoManager $manager) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(OrlandoBiblio::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $orlandoBiblios = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'orlandoBiblios' => $orlandoBiblios,
            'manager' => $manager,
        );
    }

    /**
     * Typeahead API endpoint for OrlandoBiblio entities.
     *
     * To make this work, add something like this to OrlandoBiblioRepository:
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
     * @Route("/typeahead", name="resource_orlando_biblio_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(OrlandoBiblio::class);
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
     * Search for OrlandoBiblio entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:OrlandoBiblio repository. Replace the fieldName with
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
     * @Route("/search", name="resource_orlando_biblio_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:OrlandoBiblio');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $orlandoBiblios = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $orlandoBiblios = array();
        }

        return array(
            'orlandoBiblios' => $orlandoBiblios,
            'q' => $q,
        );
    }

    /**
     * Finds and displays a OrlandoBiblio entity.
     *
     * @param OrlandoBiblio $orlandoBiblio
     *
     * @return array
     *
     * @Route("/{id}", name="resource_orlando_biblio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(OrlandoBiblio $orlandoBiblio, OrlandoManager $manager) {

        return array(
            'orlandoBiblio' => $orlandoBiblio,
            'manager' => $manager,
        );
    }

}
