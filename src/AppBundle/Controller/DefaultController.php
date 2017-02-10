<?php

namespace AppBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 * @Template()
	 */
	public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository('NinesBlogBundle:PostStatus')->findOneBy(array(
            'name' => $this->getParameter('nines_blog.published_status'
        )));
        $posts = $em->getRepository('NinesBlogBundle:Post')->findBy(array(
            'status' => $status
        ));
		// replace this example code with whatever you need
		return [
            'posts' => $posts,
		];
	}

	private function searchForm() {
		$builder = $this->createFormBuilder();
		$builder->add('title', TextType::class, array(
			'required' => false,
			'label' => 'Title'
		));
		$builder->add('person', TextType::class, array(
			'required' => false,
			'label' => 'Person',
		));
		$builder->add('person_role', ChoiceType::class, array(
			'choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Role')->findAll(),
			'choice_label' => 'name',
			'placeholder' => 'Any',
			'required' => false,
			'label' => 'Role'
		));
		$builder->add('firm', TextType::class, array(
			'required' => false,
			'label' => 'Firm',
		));
		$builder->add('firm_role', ChoiceType::class, array(
			'choices' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Firmrole')->findAll(),
			'choice_label' => 'name',
			'placeholder' => 'Any',
			'required' => false,
			'label' => 'Firm role',
		));
		return $builder->getForm();
	}

	private function buildQuery($data) {
		$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder();
		$qb->select('t')->from('AppBundle:Title', 't');
		if ($data['title']) {
			$qb->andWhere("MATCH_AGAINST(t.title, :title 'IN BOOLEAN MODE') > 0.5");
			$qb->setParameter('title', $data['title']);
		}
		if ($data['person']) {
			$qb->innerJoin('t.titleRoles', 'tr')->innerJoin('tr.person', 'p');
			$qb->andWhere("MATCH_AGAINST(p.lastName, p.firstName, p.title, :person 'IN BOOLEAN MODE') > 0.5");
			$qb->setParameter('person', $data['person']);
			if($data['person_role']) {
				$qb->innerJoin('tr.role', 'r');
				$qb->andWhere('r.id = :roleId');
				$qb->setParameter('roleId', $data['person_role']->getId());
			}
		}
		if ($data['firm']) {
			$qb->innerJoin('t.titleFirmroles', 'tfr')->innerJoin('tfr.firm', 'f');
			$qb->andWhere("MATCH_AGAINST(f.name, :firm 'IN BOOLEAN MODE') > 0.5");
			$qb->setParameter('firm', $data['firm']);
			if($data['firm_role']) {
				$qb->innerJoin('tfr.firmrole', 'fr');
				$qb->andWhere('fr.id = :firmroleId');
				$qb->setParameter('firmroleId', $data['firm_role']->getId());
			}
		}
		$qb->groupBy('t.id');
		return $qb->getQuery();
	}

	/**
	 * @Route("/search", name="search")
	 * @Template()
	 * @param Request $request
	 */
	public function searchAction(Request $request) {
		$form = $this->searchForm();
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			$data = $form->getData();
			$q = $this->buildQuery($data);
			$paginator = $this->get('knp_paginator');
			$titles = $paginator->paginate($q, $request->query->getint('page', 1), 25);
		} else {
			$titles = array();
		}

		return [
			'form' => $form->createView(),
			'titles' => $titles,
		];
	}

}
