<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Relation;
use AppBundle\Form\RelationType;

/**
 * Relation controller.
 *
 * @Route("/relation")
 */
class RelationController extends Controller
{
    /**
     * Lists all Relation entities.
     *
     * @Route("/", name="relation_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Relation e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $relations = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'relations' => $relations,
        );
    }
    /**
     * Search for Relation entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Relation repository. Replace the fieldName with
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
     * @Route("/search", name="relation_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Relation');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$relations = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$relations = array();
		}

        return array(
            'relations' => $relations,
			'q' => $q,
        );
    }
    /**
     * Full text search for Relation entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Relation repository. Replace the fieldName with
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
	 * fulltext indexes on your Relation entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="relation_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Relation');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$relations = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$relations = array();
		}

        return array(
            'relations' => $relations,
			'q' => $q,
        );
    }

    /**
     * Creates a new Relation entity.
     *
     * @Route("/new", name="relation_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $relation = new Relation();
        $form = $this->createForm('AppBundle\Form\RelationType', $relation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($relation);
            $em->flush();

            $this->addFlash('success', 'The new relation was created.');
            return $this->redirectToRoute('relation_show', array('id' => $relation->getId()));
        }

        return array(
            'relation' => $relation,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Relation entity.
     *
     * @Route("/{id}", name="relation_show")
     * @Method("GET")
     * @Template()
	 * @param Relation $relation
     */
    public function showAction(Relation $relation)
    {

        return array(
            'relation' => $relation,
        );
    }

    /**
     * Displays a form to edit an existing Relation entity.
     *
     * @Route("/{id}/edit", name="relation_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Relation $relation
     */
    public function editAction(Request $request, Relation $relation)
    {
        $editForm = $this->createForm('AppBundle\Form\RelationType', $relation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The relation has been updated.');
            return $this->redirectToRoute('relation_show', array('id' => $relation->getId()));
        }

        return array(
            'relation' => $relation,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Relation entity.
     *
     * @Route("/{id}/delete", name="relation_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Relation $relation
     */
    public function deleteAction(Request $request, Relation $relation)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($relation);
        $em->flush();
        $this->addFlash('success', 'The relation was deleted.');

        return $this->redirectToRoute('relation_index');
    }
}
