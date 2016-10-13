<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\TitleFirmrole;
use AppBundle\Form\TitleFirmroleType;

/**
 * TitleFirmrole controller.
 *
 * @Route("/titlefirmrole")
 */
class TitleFirmroleController extends Controller
{
    /**
     * Lists all TitleFirmrole entities.
     *
     * @Route("/", name="titlefirmrole_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:TitleFirmrole e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $titleFirmroles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'titleFirmroles' => $titleFirmroles,
        );
    }
    /**
     * Search for TitleFirmrole entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:TitleFirmrole repository. Replace the fieldName with
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
     * @Route("/search", name="titlefirmrole_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:TitleFirmrole');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$titleFirmroles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$titleFirmroles = array();
		}

        return array(
            'titleFirmroles' => $titleFirmroles,
			'q' => $q,
        );
    }
    /**
     * Full text search for TitleFirmrole entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:TitleFirmrole repository. Replace the fieldName with
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
	 * fulltext indexes on your TitleFirmrole entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="titlefirmrole_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:TitleFirmrole');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$titleFirmroles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$titleFirmroles = array();
		}

        return array(
            'titleFirmroles' => $titleFirmroles,
			'q' => $q,
        );
    }

    /**
     * Creates a new TitleFirmrole entity.
     *
     * @Route("/new", name="titlefirmrole_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $titleFirmrole = new TitleFirmrole();
        $form = $this->createForm('AppBundle\Form\TitleFirmroleType', $titleFirmrole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($titleFirmrole);
            $em->flush();

            $this->addFlash('success', 'The new titleFirmrole was created.');
            return $this->redirectToRoute('titlefirmrole_show', array('id' => $titleFirmrole->getId()));
        }

        return array(
            'titleFirmrole' => $titleFirmrole,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a TitleFirmrole entity.
     *
     * @Route("/{id}", name="titlefirmrole_show")
     * @Method("GET")
     * @Template()
	 * @param TitleFirmrole $titleFirmrole
     */
    public function showAction(TitleFirmrole $titleFirmrole)
    {

        return array(
            'titleFirmrole' => $titleFirmrole,
        );
    }

    /**
     * Displays a form to edit an existing TitleFirmrole entity.
     *
     * @Route("/{id}/edit", name="titlefirmrole_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param TitleFirmrole $titleFirmrole
     */
    public function editAction(Request $request, TitleFirmrole $titleFirmrole)
    {
        $editForm = $this->createForm('AppBundle\Form\TitleFirmroleType', $titleFirmrole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The titleFirmrole has been updated.');
            return $this->redirectToRoute('titlefirmrole_show', array('id' => $titleFirmrole->getId()));
        }

        return array(
            'titleFirmrole' => $titleFirmrole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a TitleFirmrole entity.
     *
     * @Route("/{id}/delete", name="titlefirmrole_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param TitleFirmrole $titleFirmrole
     */
    public function deleteAction(Request $request, TitleFirmrole $titleFirmrole)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($titleFirmrole);
        $em->flush();
        $this->addFlash('success', 'The titleFirmrole was deleted.');

        return $this->redirectToRoute('titlefirmrole_index');
    }
}
