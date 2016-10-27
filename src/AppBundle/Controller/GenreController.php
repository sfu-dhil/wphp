<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Genre;
use AppBundle\Form\GenreType;

/**
 * Genre controller.
 *
 * @Route("/genre")
 */
class GenreController extends Controller
{
    /**
     * Lists all Genre entities.
     *
     * @Route("/", name="genre_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Genre e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $genres = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'genres' => $genres,
        );
    }
    /**
     * Search for Genre entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Genre repository. Replace the fieldName with
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
     * @Route("/search", name="genre_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Genre');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$genres = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$genres = array();
		}

        return array(
            'genres' => $genres,
			'q' => $q,
        );
    }
    /**
     * Full text search for Genre entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Genre repository. Replace the fieldName with
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
	 * fulltext indexes on your Genre entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="genre_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Genre');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$genres = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$genres = array();
		}

        return array(
            'genres' => $genres,
			'q' => $q,
        );
    }

    /**
     * Creates a new Genre entity.
     *
     * @Route("/new", name="genre_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $genre = new Genre();
        $form = $this->createForm('AppBundle\Form\GenreType', $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'The new genre was created.');
            return $this->redirectToRoute('genre_show', array('id' => $genre->getId()));
        }

        return array(
            'genre' => $genre,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Genre entity.
     *
     * @Route("/{id}", name="genre_show")
     * @Method("GET")
     * @Template()
	 * @param Genre $genre
     */
    public function showAction(Genre $genre)
    {

        return array(
            'genre' => $genre,
        );
    }

    /**
     * Displays a form to edit an existing Genre entity.
     *
     * @Route("/{id}/edit", name="genre_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Genre $genre
     */
    public function editAction(Request $request, Genre $genre)
    {
        $editForm = $this->createForm('AppBundle\Form\GenreType', $genre);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The genre has been updated.');
            return $this->redirectToRoute('genre_show', array('id' => $genre->getId()));
        }

        return array(
            'genre' => $genre,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Genre entity.
     *
     * @Route("/{id}/delete", name="genre_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Genre $genre
     */
    public function deleteAction(Request $request, Genre $genre)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($genre);
        $em->flush();
        $this->addFlash('success', 'The genre was deleted.');

        return $this->redirectToRoute('genre_index');
    }
}
