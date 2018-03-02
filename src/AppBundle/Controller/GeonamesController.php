<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Geonames;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Geonames controller.
 *
 * @Route("/geonames")
 */
class GeonamesController extends Controller
{
    /**
     * Lists all Geonames entities.
     *
     * @Route("/", name="geonames_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Geonames e ORDER BY e.geonameid';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $geonames = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'geonames' => $geonames,
        );
    }

    /**
     * @param Request $request
     * @Route("/typeahead", name="geonames_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request) {
        error_log($request);
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Geonames::class);
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getGeonameid(),
                'text' => $result->getName(),
            ];
        }
        
        return new JsonResponse($data);
    }
    
    
    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}", name="geonames_show")
     * @Method("GET")
     * @Template()
     * @param Geonames $geoname
     */
    public function showAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT t FROM AppBundle:Title t WHERE t.locationOfPrinting = :geoname ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $paginator = $this->get('knp_paginator');
        $titles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'geoname' => $geoname,
            'titles' => $titles,
        );
    }
    
    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/titles", name="geonames_titles")
     * @Method("GET")
     * @Template()
     * @param Geonames $geoname
     */
    public function titlesAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT t FROM AppBundle:Title t WHERE t.locationOfPrinting = :geoname ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $paginator = $this->get('knp_paginator');
        $titles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'geoname' => $geoname,
            'titles' => $titles,
        );
    }
    
        /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/firms", name="geonames_firms")
     * @Method("GET")
     * @Template()
     * @param Geonames $geoname
     */
    public function firmsAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT f FROM AppBundle:Firm f WHERE f.city = :geoname ORDER BY f.name';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $paginator = $this->get('knp_paginator');
        $firms = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'geoname' => $geoname,
            'firms' => $firms,
        );
    }
    
    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}/people", name="geonames_people")
     * @Method("GET")
     * @Template()
     * @param Geonames $geoname
     */
    public function peopleAction(Request $request, Geonames $geoname) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT p FROM AppBundle:Person p WHERE (p.cityOfBirth = :geoname) OR (p.cityOfDeath = :geoname) ORDER BY p.lastName, p.firstName';
        $query = $em->createQuery($dql);
        $query->setParameter('geoname', $geoname);
        $paginator = $this->get('knp_paginator');
        $people = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'geoname' => $geoname,
            'people' => $people,
        );
    }

}
