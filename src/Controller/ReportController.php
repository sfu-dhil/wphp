<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Title;
use App\Form\Title\TitleCheckFilterType;
use App\Form\Title\TitleSourceFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\Query\Expr;

/**
 * Reports controller.
 */
#[Route(path: '/report')]
#[Security("is_granted('ROLE_CONTENT_ADMIN')")]
class ReportController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Index for all reports.
     */
    #[Route(path: '/', name: 'report_index', methods: ['GET'])]
    #[Template]
    public function indexAction(UrlGeneratorInterface $router, Request $request, EntityManagerInterface $em) : array {
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
     */
    #[Route(path: '/titles_fc', name: 'report_titles_check', methods: ['GET'])]
    #[Template]
    public function titlesFinalCheckAction(Request $request, EntityManagerInterface $em) : array {
        $form = $this->createForm(TitleSourceFilterType::class);
        $form->handleRequest($request);

        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->where('title.finalcheck = 0 AND title.finalattempt = 0')
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();

            $qb->leftJoin('title.titleSources', 'ts');
            if ($filter->getSource()) {
                $qb->andWhere('ts.source = :source');
                $qb->setParameter('source', $filter->getSource());
            }
            if ($filter->getIdentifier()) {
                $qb->andWhere('MATCH(ts.identifier) AGAINST(:identifier BOOLEAN) > 0');
                $qb->setParameter('identifier', $filter->getIdentifier());
            }
        }

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles to Final Check',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
            'search_form' => $form->createView(),
        ];
    }

    /**
     * List titles missing a genre.
     */
    #[Route(path: '/titles_genre', name: 'report_titles_genre', methods: ['GET'])]
    #[Template]
    public function titlesGenreAction(Request $request, EntityManagerInterface $em) : array {
        $form = $this->createForm(TitleCheckFilterType::class);
        $form->handleRequest($request);

        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->leftJoin('title.genres', 'g')
            ->where('g.id IS NULL')
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();
            if (null !== $filter->getFinalcheck()) {
                $qb->andWhere('title.finalcheck = :finalcheck');
                $qb->setParameter('finalcheck', $filter->getFinalcheck());
            }
            if (null !== $filter->getFinalattempt()) {
                $qb->andWhere('title.finalattempt = :finalattempt');
                $qb->setParameter('finalattempt', $filter->getFinalattempt());
            }
        }

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'heading' => 'Titles Without a Genre',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
            'search_form' => $form->createView(),
        ];
    }

    #[Route(path: '/titles_multi_genres', name: 'report_titles_multi_genres', methods: ['GET'])]
    #[Template]
    public function titlesMultiGenres(Request $request, EntityManagerInterface $em) : array {
        $form = $this->createForm(TitleCheckFilterType::class);
        $form->handleRequest($request);

        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->addSelect('COUNT(g.id) AS HIDDEN genre_count')
            ->from(Title::class, 'title')
            ->join('title.genres', 'g')
            ->andHaving('genre_count >= 2')
            ->groupBy('title.id')
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();
            if (null !== $filter->getFinalcheck()) {
                $qb->andWhere('title.finalcheck = :finalcheck');
                $qb->setParameter('finalcheck', $filter->getFinalcheck());
            }
            if (null !== $filter->getFinalattempt()) {
                $qb->andWhere('title.finalattempt = :finalattempt');
                $qb->setParameter('finalattempt', $filter->getFinalattempt());
            }
        }

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25, [
            'wrap-queries' => true
        ]);

        return [
            'heading' => 'Titles with Multiple Genres',
            'titles' => $titles,
            'count' => $titles->getTotalItemCount(),
            'search_form' => $form->createView(),
        ];
    }

    /**
     * List bad publication dates for titles.
     */
    #[Route(path: '/titles_date', name: 'report_titles_date', methods: ['GET'])]
    #[Template]
    public function titlesDateAction(Request $request, EntityManagerInterface $em) : array {
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
     */
    #[Route(path: '/persons_fc', name: 'report_persons_fc', methods: ['GET'])]
    #[Template]
    public function personsFinalCheckAction(Request $request, EntityManagerInterface $em) : array {
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
     */
    #[Route(path: '/editions', name: 'report_editions', methods: ['GET'])]
    #[Template]
    public function editionsAction(Request $request, EntityManagerInterface $em) : array {
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
     */
    #[Route(path: '/editions_check', name: 'report_editions_to_check', methods: ['GET'])]
    #[Template]
    public function editionsToCheckAction(Request $request, EntityManagerInterface $em) : array {
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

    #[Route(path: '/titles_unverified_persons', name: 'titles_unverified_persons', methods: ['GET'])]
    #[Template]
    public function titlesWithUnverifiedPersons(Request $request, EntityManagerInterface $em) : array {
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

    #[Route(path: '/titles_unverified_firms', name: 'titles_unverified_firms', methods: ['GET'])]
    #[Template]
    public function titlesWithUnverifiedFirms(Request $request, EntityManagerInterface $em) : array {
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

    #[Route(path: '/titles_unverified', name: 'titles_unverified', methods: ['GET'])]
    #[Template]
    public function unverifiedTitles(Request $request, EntityManagerInterface $em) : array {
        /**
         * List the ESTC as a source
         * Are not final-checked, not hand-checked, and not attempted verified
         * That have a publication date before 1800 (inclusive).
         */
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->where("(CAST(REGEXP_REPLACE(title.pubdate,'^[^0-9]+', '') AS UNSIGNED) <= 1800 OR title.pubdate IS NULL)")
            ->andWhere('(title.checked = 0 AND title.finalattempt = 0 AND title.finalcheck = 0)')
            ->leftJoin('title.titleSources', 'ts', Expr\Join::WITH, $qb->expr()->eq('ts.source', 75))
            ->andWhere('ts.id IS NULL')
        ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25, [
            'defaultSortFieldName' => ['title.title', 'title.pubdate'],
            'defaultSortDirection' => 'asc',
        ]);

        return [
            'heading' => 'Unverified Pre-1800 Titles (excluding AAS)',
            'titles' => $titles,
            'sortable' => true,
            'count' => $titles->getTotalItemCount(),
        ];
    }
}
