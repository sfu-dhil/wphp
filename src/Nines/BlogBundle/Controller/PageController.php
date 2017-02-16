<?php

namespace Nines\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Nines\BlogBundle\Entity\Page;
use Nines\BlogBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * Lists all Page entities.
     *
     * @Route("/", name="page_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM NinesBlogBundle:Page e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pages = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'pages' => $pages,
        );
    }

    /**
     * @Route("/sort", name="page_sort")
     * @Method({"GET","POST"})
     * @Template()
     * 
     * @param Request $request
     * @return array
     */
    public function sortAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('NinesBlogBundle:Page');
        
        if($request->getMethod() === 'POST') {
            $order = $request->request->get('order');
            $list = explode(',', $order);
            for($i = 0; $i < count($list); ++$i) {
                $page = $repo->find($list[$i]);
                $page->setWeight($i+1);
                $em->flush($page);
            }
            $this->addFlash('success', 'The pages have been ordered.');
            return $this->redirect($this->generateUrl('page_sort'));
        }
        
        $pages = $repo->findBy(
            array('public' => true), 
            array('weight' => 'ASC', 'title' => 'ASC')
        );
        return array(
            'pages' => $pages
        );
    }
    
    /**
     * Search for Page entities.
	 *
	 * To make this work, add a method like this one to the 
	 * NinesBlogBundle:Page repository. Replace the fieldName with
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
     * @Route("/search", name="page_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('NinesBlogBundle:Page');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$pages = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$pages = array();
		}

        return array(
            'pages' => $pages,
			'q' => $q,
        );
    }
    /**
     * Full text search for Page entities.
	 *
	 * To make this work, add a method like this one to the 
	 * NinesBlogBundle:Page repository. Replace the fieldName with
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
	 * fulltext indexes on your Page entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="page_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('NinesBlogBundle:Page');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$pages = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$pages = array();
		}

        return array(
            'pages' => $pages,
			'q' => $q,
        );
    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/new", name="page_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $page = new Page();
        $page->setUser($user);
        
        $form = $this->createForm('Nines\BlogBundle\Form\PageType', $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( ! $page->getExcerpt()) {
                $page->setExcerpt($this->get('nines.util.word_trim')->trim($page->getContent(), $this->getParameter('nines_blog.excerpt_length')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', 'The new page was created.');
            return $this->redirectToRoute('page_show', array('id' => $page->getId()));
        }

        return array(
            'page' => $page,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}", name="page_show")
     * @Method("GET")
     * @Template()
	 * @param Page $page
     */
    public function showAction(Page $page)
    {

        return array(
            'page' => $page,
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="page_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Page $page
     */
    public function editAction(Request $request, Page $page)
    {
        $editForm = $this->createForm('Nines\BlogBundle\Form\PageType', $page);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if( ! $page->getExcerpt()) {
                $page->setExcerpt($this->get('nines.util.word_trim')->trim($page->getContent(), $this->getParameter('nines_blog.excerpt_length')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The page has been updated.');
            return $this->redirectToRoute('page_show', array('id' => $page->getId()));
        }

        return array(
            'page' => $page,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}/delete", name="page_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Page $page
     */
    public function deleteAction(Request $request, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        $this->addFlash('success', 'The page was deleted.');

        return $this->redirectToRoute('page_index');
    }
}
