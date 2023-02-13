<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Title;
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
     * Index for all reports.
     *
     * @Route("/", name="report_index", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $router = $this->get('router');
        $routeCollection = $router->getRouteCollection()->all();
        $reportRoutes = array_filter($routeCollection, fn ($route) => preg_match('/\/report\/.+/', $route->getPath()));
        $reports = [];
        foreach ($reportRoutes as $route) {
            $defaults = $route->getDefaults();
            $path = $route->getPath();
            $controller = $defaults['_controller'];
            $bits = preg_split('/:+/', $controller);
            $method = end($bits);
            $reports[$path] = $this->{$method}($request, $em);
        }

        return [
            'reports' => $reports,
        ];
    }

    /**
     * List titles that need to be final checked.
     *
     * @Route("/titles_fc", name="report_titles_check", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function titlesFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Title e WHERE e.finalcheck = 0 AND e.finalattempt = 0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles to Final Check',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
        ];
    }

    /**
     * List bad publication dates for titles.
     *
     * @Route("/titles_date", name="report_titles_date", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function titlesDateAction(Request $request, EntityManagerInterface $em) {
        $dql = "SELECT e FROM App:Title e WHERE e.pubdate IS NOT NULL AND e.pubdate != '' AND regexp(e.pubdate,'[^0-9-]') = 1";
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles with Bad Publication Date',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/firms_fc", name="report_firms_fc", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function firmsFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Firm e WHERE e.finalcheck != 1';
        $query = $em->createQuery($dql);
        $firms = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Firms to Final Check',
            'firms' => $firms,
            'count' => $firms->getTotalItemCount(),
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/persons_fc", name="report_persons_fc", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function personsFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Person e WHERE e.finalcheck != 1';
        $query = $em->createQuery($dql);
        $persons = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Persons to Final Check',
            'persons' => $persons,
            'count' => $persons->getTotalItemCount(),

        ];
    }

    /**
     * List of titles where the edition field contains a number
     * or the words "Irish" or "American".
     *
     * @Route("/editions", name="report_editions", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function editionsAction(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->orWhere("title.edition LIKE '%irish%'")
            ->orWhere("title.edition LIKE '%american%'")
            ->orWhere("regexp(title.edition, '[0-9]') = 1")
            ->orderBy('title.id', 'ASC')
        ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles with numerical, Irish, or American Editions',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
        ];
    }

    /**
     * Titles that do.
     *
     * @Route("/editions_check", name="report_editions_to_check", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function editionsToCheckAction(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->where('title.editionChecked = 0')
            ->orderBy('title.id', 'ASC')
        ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles with Editions to Check',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
        ];
    }

    /**
     * @Route("/titles_unverified_persons", name="titles_unverified_persons", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function titlesWithUnverifiedPersons(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->innerJoin('title.titleRoles', 'tr')
            ->innerJoin('tr.person', 'p')
            ->where('p.finalcheck = 0')
            ->andWhere('(title.checked = 1 OR title.finalattempt = 1 OR title.finalcheck = 1)')
        ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles with Unverified Persons',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
        ];
    }

    /**
     * @Route("/titles_unverified_firms", name="titles_unverified_firms", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function titlesWithUnverifiedFirms(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->innerJoin('title.titleFirmroles', 'tfr')
            ->innerJoin('tfr.firm', 'f')
            ->where('f.finalcheck = 0')
            ->andWhere('(title.checked = 1 OR title.finalattempt = 1 OR title.finalcheck = 1)')
        ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles with Unverified Firms',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
        ];
    }

    /**
     * @Route("/unchecked_aas_titles", name="unchecked_aas_titles", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function uncheckedAasTitles(Request $request, EntityManagerInterface $em) {
        /**
         * List the American Antiquarian Society (AAS) as a source
         * Are not final-checked, not hand-checked, and not attempted verified
         * That have a publication date between 1801 and 1819 (inclusive).
         */
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->where("1801 <= YEAR(STRTODATE(title.pubdate, '%Y')) AND YEAR(STRTODATE(title.pubdate, '%Y')) <= 1819")
            ->andWhere('(title.checked = 0 AND title.finalattempt = 0 AND title.finalcheck = 0)')
            ->innerJoin('title.titleSources', 'ts')
            ->andWhere('ts.source = 75')
            ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25, [
            'defaultSortFieldName' => ['title.title', 'title.pubdate'],
            'defaultSortDirection' => 'asc',
        ]);

        return [
            'heading' => 'AAS Titles (1801-1819)',
            'titles' => $titles,
            'sortable' => true,
            'count' => $titles->getTotalItemCount(),
        ];
    }
}
