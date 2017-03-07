<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Entity\Title;
use AppBundle\Form\PersonSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends Controller
{
    /**
     * Lists all Person entities.
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Person e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $people = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'people' => $people,
        );
    }
	
    /**
     * Full text search for Person entities.
     *
     * @Route("/search", name="person_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PersonSearchType::class, null, array('entity_manager' => $em));
        $form->handleRequest($request);
        $persons = array();
        
        if($form->isValid()) {
            $repo = $em->getRepository(Person::class);
            $query = $repo->buildSearchQuery($form->getData());
            $paginator = $this->get('knp_paginator');        
            $persons = $paginator->paginate($query->execute(), $request->query->getint('page', 1), 25);
        } 
        return array(
            'search_form' => $form->createView(),
            'persons' => $persons,
        );
    }

    /**
     * Search for Title entities.
     *
     * @Route("/jump", name="person_jump")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function jumpAction(Request $request)
    {
		$q = $request->query->get('q');
		if($q) {
            return $this->redirect($this->generateUrl('person_show', array('id' => $q)));
		} else {
            return $this->redirect($this->generateUrl('person_index', array('id' => $q)));
		}
    }
    
    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}", name="person_show")
     * @Method({"GET","POST"})
     * @Template()
	 * @param Person $person
     */
    public function showAction(Request $request, Person $person)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Person');        
		return array(
            'person' => $person,
            'next' => $repo->next($person),
            'previous' => $repo->previous($person),
        );
    }
}
