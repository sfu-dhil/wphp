<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Title;
use App\Entity\TitleRole;
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
     * List titles that need to be final checked.
     *
     * @Route("/titles_fc", name="report_titles_check", methods={"GET"})
     * @Template
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

    /**
     * List bad publication dates for titles.
     *
     * @Route("/titles_date", name="report_titles_date", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function titlesDateAction(Request $request, EntityManagerInterface $em) {
        $dql = "SELECT e FROM App:Title e WHERE e.pubdate IS NOT NULL AND e.pubdate != '' AND regexp(e.pubdate,'[^0-9-]') = 1";
        $query = $em->createQuery($dql);
        $titles = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'titles' => $titles,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/firms_fc", name="report_firms_fc", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function firmsFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Firm e WHERE e.finalcheck != 1';
        $query = $em->createQuery($dql);
        $firms = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'firms' => $firms,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/persons_fc", name="report_persons_fc", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function personsFinalCheckAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Person e WHERE e.finalcheck != 1';
        $query = $em->createQuery($dql);
        $persons = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return [
            'persons' => $persons,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/editions", name="report_editions", methods={"GET"})
     * @Template
     *
     * @return array
     */
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
            'titles' => $titles,
        ];
    }

    /**
     * List firms that have not been checked.
     *
     * @Route("/editions_check", name="report_editions_to_check", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function editionsToCheckAction(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->where('title.editionChecked = 0')
            ->orderBy('title.id', 'ASC')
        ;

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'titles' => $titles,
        ];
    }

    /**
     * @Route("/titles_unverified_persons", name="titles_unverified_persons", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function titlesWithUnverifiedPersons(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->innerJoin('title.titleRoles', 'tr')
            ->innerJoin('tr.person', 'p')
            ->where('p.finalcheck = 0')
            ->andWhere('(title.checked = 1 OR title.finalattempt = 1 OR title.finalcheck = 1)');

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'titles' => $titles,
        ];
    }

    /**
     * @Route("/titles_unverified_firms", name="titles_unverified_firms", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function titlesWithUnverifiedFirms(Request $request, EntityManagerInterface $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('title')
            ->from(Title::class, 'title')
            ->innerJoin('title.titleFirmroles', 'tfr')
            ->innerJoin('tfr.firm', 'f')
            ->where('f.finalcheck = 0')
            ->andWhere('(title.checked = 1 OR title.finalattempt = 1 OR title.finalcheck = 1)');

        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return [
            'titles' => $titles,
        ];
    }
}
