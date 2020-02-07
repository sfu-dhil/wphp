<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Reports controller.
 *
 * @Route("/report")
 * @Security("is_granted('ROLE_CONTENT_ADMIN')")
 */
class ReportController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * List bad dates of publication.
     *
     * @Route("/titles_fc", name="report_titles_check", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function titlesFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Title e WHERE e.finalcheck = 0 AND e.finalattempt = 0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'titles' => $titles,
        ];
    }
}
