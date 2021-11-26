<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Repository\ReportRepository;
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
     * List titles that need to be final checked.
     *
     * @Route("/titles_fc", name="report_titles_check", methods={"GET"})
     * @Template
     */
    public function titlesFinalCheckAction(Request $request, ReportRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->titlesFinalCheckQuery();
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), $pageSize);

        return [
            'titles' => $titles,
        ];
    }

    /**
     * List bad publication dates for titles.
     *
     * @Route("/titles_date", name="report_titles_date", methods={"GET"})
     * @Template
     */
    public function titlesDateAction(Request $request, ReportRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->titlesDateQuery();
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), $pageSize);

        return [
            'titles' => $titles,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/firms_fc", name="report_firms_fc", methods={"GET"})
     * @Template
     */
    public function firmsFinalCheckAction(Request $request, ReportRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->firmsFinalCheckQuery();
        $firms = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), $pageSize);

        return [
            'firms' => $firms,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/persons_fc", name="report_persons_fc", methods={"GET"})
     * @Template
     */
    public function personsFinalCheckAction(Request $request, ReportRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->personsFinalCheckQuery();
        $persons = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), $pageSize);

        return [
            'persons' => $persons,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/editions", name="report_editions", methods={"GET"})
     * @Template
     */
    public function editionsAction(Request $request, ReportRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->editionsQuery();
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);

        return [
            'titles' => $titles,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/editions_check", name="report_editions_to_check", methods={"GET"})
     * @Template
     */
    public function editionsToCheckAction(Request $request, ReportRepository $repository) : array {
        $pageSize = $this->getParameter('page_size');
        $query = $repository->editionsToCheckQuery();
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), $pageSize);

        return [
            'titles' => $titles,
        ];
    }
}
