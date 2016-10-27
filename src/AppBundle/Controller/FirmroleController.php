<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Firmrole;
use AppBundle\Form\FirmroleType;

/**
 * Firmrole controller.
 *
 * @Route("/firmrole")
 */
class FirmroleController extends Controller
{
    /**
     * Lists all Firmrole entities.
     *
     * @Route("/", name="firmrole_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
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
     * Search for Firmrole entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Firmrole repository. Replace the fieldName with
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
     * @Route("/search", name="firmrole_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Firmrole');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$firmroles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$firmroles = array();
		}

        return array(
            'firmroles' => $firmroles,
			'q' => $q,
        );
    }
    /**
     * Full text search for Firmrole entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Firmrole repository. Replace the fieldName with
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
	 * fulltext indexes on your Firmrole entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="firmrole_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Firmrole');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$firmroles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$firmroles = array();
		}

        return array(
            'firmroles' => $firmroles,
			'q' => $q,
        );
    }

    /**
     * Creates a new Firmrole entity.
     *
     * @Route("/new", name="firmrole_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $firmrole = new Firmrole();
        $form = $this->createForm('AppBundle\Form\FirmroleType', $firmrole);
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
    public function showAction(Firmrole $firmrole)
    {

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
	 * @param Request $request
	 * @param Firmrole $firmrole
     */
    public function editAction(Request $request, Firmrole $firmrole)
    {
        $editForm = $this->createForm('AppBundle\Form\FirmroleType', $firmrole);
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
	 * @param Request $request
	 * @param Firmrole $firmrole
     */
    public function deleteAction(Request $request, Firmrole $firmrole)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($firmrole);
        $em->flush();
        $this->addFlash('success', 'The firmrole was deleted.');

        return $this->redirectToRoute('firmrole_index');
    }
}
