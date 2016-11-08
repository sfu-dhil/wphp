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

}
