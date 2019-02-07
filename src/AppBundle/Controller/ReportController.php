<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
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
    public function firstPubDateAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM AppBundle:Title e WHERE REGEXP(e.dateOfFirstPublication, \'^[0-9]{4}$\')=0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

    /**
     * List bad dates of publication
     *
     * @Route("/titles_fc", name="report_titles_check")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function titlesFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM AppBundle:Title e WHERE e.finalcheck = 0 AND e.finalattempt = 0 AND e.pubdate <= 1800 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }
    
    /**
     * List bad dates of publication
     *
     * @Route("/firms_fc", name="report_firms_check")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function firmsFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM AppBundle:Firm e WHERE e.finalcheck = 0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $firms = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'firms' => $firms,
        );
    }
    
    /**
     * List bad dates of publication
     *
     * @Route("/persons_fc", name="report_person_check")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function personsFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM AppBundle:Person e WHERE e.finalcheck = 0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $persons = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'persons' => $persons,
        );
    }
    
}
