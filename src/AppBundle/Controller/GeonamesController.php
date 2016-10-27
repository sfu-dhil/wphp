<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Geonames;
use AppBundle\Form\GeonamesType;

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
    public function indexAction(Request $request)
    {
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
     * Search for Geonames entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Geonames repository. Replace the fieldName with
	 * something appropriate, and adjust the generated search.html.twig
	 * template.
	 * 
     //    public function searchQuery($q) {
     //        $qb = $this->createQueryBuilder('e');
     //        $qb->where("e.fieldName like '%$q%'");
     //        return $qb->getQuery();
     //    }
	 *
     *
     * @Route("/search", name="geonames_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Geonames');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$geonames = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$geonames = array();
		}

        return array(
            'geonames' => $geonames,
			'q' => $q,
        );
    }
    /**
     * Full text search for Geonames entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Geonames repository. Replace the fieldName with
	 * something appropriate, and adjust the generated fulltext.html.twig
	 * template.
	 * 
	//    public function fulltextQuery($q) {
	//        $qb = $this->createQueryBuilder('e');
	//        $qb->addSelect("MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') as score");
	//        $qb->add('where', "MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') > 0.5");
	//        $qb->orderBy('score', 'desc');
	//        $qb->setParameter('q', $q);
	//        return $qb->getQuery();
	//    }	 
	 * 
	 * Requires a MatchAgainst function be added to doctrine, and appropriate
	 * fulltext indexes on your Geonames entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="geonames_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Geonames');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$geonames = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$geonames = array();
		}

        return array(
            'geonames' => $geonames,
			'q' => $q,
        );
    }

    /**
     * Creates a new Geonames entity.
     *
     * @Route("/new", name="geonames_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $geoname = new Geonames();
        $form = $this->createForm('AppBundle\Form\GeonamesType', $geoname);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($geoname);
            $em->flush();

            $this->addFlash('success', 'The new geoname was created.');
            return $this->redirectToRoute('geonames_show', array('id' => $geoname->getId()));
        }

        return array(
            'geoname' => $geoname,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Geonames entity.
     *
     * @Route("/{id}", name="geonames_show")
     * @Method("GET")
     * @Template()
	 * @param Geonames $geoname
     */
    public function showAction(Geonames $geoname)
    {

        return array(
            'geoname' => $geoname,
        );
    }

    /**
     * Displays a form to edit an existing Geonames entity.
     *
     * @Route("/{id}/edit", name="geonames_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Geonames $geoname
     */
    public function editAction(Request $request, Geonames $geoname)
    {
        $editForm = $this->createForm('AppBundle\Form\GeonamesType', $geoname);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The geoname has been updated.');
            return $this->redirectToRoute('geonames_show', array('id' => $geoname->getId()));
        }

        return array(
            'geoname' => $geoname,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Geonames entity.
     *
     * @Route("/{id}/delete", name="geonames_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Geonames $geoname
     */
    public function deleteAction(Request $request, Geonames $geoname)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($geoname);
        $em->flush();
        $this->addFlash('success', 'The geoname was deleted.');

        return $this->redirectToRoute('geonames_index');
    }
}
