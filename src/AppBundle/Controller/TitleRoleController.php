<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\TitleRole;
use AppBundle\Form\TitleRoleType;

/**
 * TitleRole controller.
 *
 * @Route("/titlerole")
 */
class TitleRoleController extends Controller
{
    /**
     * Lists all TitleRole entities.
     *
     * @Route("/", name="titlerole_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:TitleRole e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $titleRoles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'titleRoles' => $titleRoles,
        );
    }
    /**
     * Search for TitleRole entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:TitleRole repository. Replace the fieldName with
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
     * @Route("/search", name="titlerole_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:TitleRole');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$titleRoles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$titleRoles = array();
		}

        return array(
            'titleRoles' => $titleRoles,
			'q' => $q,
        );
    }
    /**
     * Full text search for TitleRole entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:TitleRole repository. Replace the fieldName with
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
	 * fulltext indexes on your TitleRole entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="titlerole_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:TitleRole');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$titleRoles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$titleRoles = array();
		}

        return array(
            'titleRoles' => $titleRoles,
			'q' => $q,
        );
    }

    /**
     * Creates a new TitleRole entity.
     *
     * @Route("/new", name="titlerole_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $titleRole = new TitleRole();
        $form = $this->createForm('AppBundle\Form\TitleRoleType', $titleRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($titleRole);
            $em->flush();

            $this->addFlash('success', 'The new titleRole was created.');
            return $this->redirectToRoute('titlerole_show', array('id' => $titleRole->getId()));
        }

        return array(
            'titleRole' => $titleRole,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a TitleRole entity.
     *
     * @Route("/{id}", name="titlerole_show")
     * @Method("GET")
     * @Template()
	 * @param TitleRole $titleRole
     */
    public function showAction(TitleRole $titleRole)
    {

        return array(
            'titleRole' => $titleRole,
        );
    }

    /**
     * Displays a form to edit an existing TitleRole entity.
     *
     * @Route("/{id}/edit", name="titlerole_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param TitleRole $titleRole
     */
    public function editAction(Request $request, TitleRole $titleRole)
    {
        $editForm = $this->createForm('AppBundle\Form\TitleRoleType', $titleRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The titleRole has been updated.');
            return $this->redirectToRoute('titlerole_show', array('id' => $titleRole->getId()));
        }

        return array(
            'titleRole' => $titleRole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a TitleRole entity.
     *
     * @Route("/{id}/delete", name="titlerole_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param TitleRole $titleRole
     */
    public function deleteAction(Request $request, TitleRole $titleRole)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($titleRole);
        $em->flush();
        $this->addFlash('success', 'The titleRole was deleted.');

        return $this->redirectToRoute('titlerole_index');
    }
}
