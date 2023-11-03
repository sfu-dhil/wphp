<?php

declare(strict_types=1);

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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Reports controller.
 */
#[Route(path: '/report')]
#[Security("is_granted('ROLE_CONTENT_ADMIN')")]
class ReportController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Index for all reports.
     *
     * @return array<string,mixed>
     */
    #[Route(path: '/', name: 'report_index', methods: ['GET'])]
    #[Template]
    public function indexAction(UrlGeneratorInterface $router, Request $request, EntityManagerInterface $em) {
        $routeCollection = $router->getRouteCollection()->all();
        $reportRoutes = array_filter($routeCollection, fn ($route) => preg_match('/\/report\/.+/', (string) $route->getPath()));
        $reports = [];
        foreach ($reportRoutes as $route) {
            $defaults = $route->getDefaults();
            $path = $route->getPath();
            $controller = $defaults['_controller'];
            $bits = preg_split('/:+/', (string) $controller);
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
     * @return array<string,mixed>     */
    #[Route(path: '/titles_fc', name: 'report_titles_check', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/titles_date', name: 'report_titles_date', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/firms_fc', name: 'report_firms_fc', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/persons_fc', name: 'report_persons_fc', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/editions', name: 'report_editions', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/editions_check', name: 'report_editions_to_check', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/titles_unverified_persons', name: 'titles_unverified_persons', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/titles_unverified_firms', name: 'titles_unverified_firms', methods: ['GET'])]
    #[Template]
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
     * @return array<string,mixed>     */
    #[Route(path: '/titles_unverified_estc', name: 'titles_unverified_estc', methods: ['GET'])]
    #[Template]
    public function unverifiedEstcTitles(Request $request, EntityManagerInterface $em) {
        /**
         * List the ESTC as a source
         * Are not final-checked, not hand-checked, and not attempted verified
         * That have a publication date before 1750 (inclusive).
         */
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->where("YEAR(STRTODATE(title.pubdate, '%Y')) < 1750")
            ->andWhere('(title.checked = 0 AND title.finalattempt = 0 AND title.finalcheck = 0)')
            ->innerJoin('title.titleSources', 'ts')
            ->andWhere('ts.source = 2')
        ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25, [
            'defaultSortFieldName' => ['title.title', 'title.pubdate'],
            'defaultSortDirection' => 'asc',
        ]);

        return [
            'heading' => 'ESTC Titles (pre 1750)',
            'titles' => $titles,
            'sortable' => true,
            'count' => $titles->getTotalItemCount(),
        ];
    }
}
