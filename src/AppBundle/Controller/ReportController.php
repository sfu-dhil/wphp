<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Reports controller.
 *
 * @Route("/report")
 * @Security("has_role('ROLE_CONTENT_ADMIN')")
 */
class ReportController extends Controller implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * List bad dates of publication.
     *
     * @Route("/titles_fc", name="report_titles_check", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titlesFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM AppBundle:Title e WHERE e.finalcheck = 0 AND e.finalattempt = 0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }
}
