<?php

namespace AppBundle\Controller;

use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reports controller.
 *
 * @Route("/report")
 * @Security("has_role('ROLE_CONTENT_ADMIN')")
 */
class ReportController extends Controller  implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * List bad dates of publication
     *
     * @Route("/first_pub_date", name="report_first_pub_date")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function firstPubDateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Title e WHERE REGEXP(e.dateOfFirstPublication, \'^[0-9]{4}$\')=0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

}
