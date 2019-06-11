<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EstcMarc;
use AppBundle\Entity\Source;
use AppBundle\Entity\TitleFirmrole;
use AppBundle\Entity\TitleSource;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reports controller.
 * @Route("/report")
 * @Security("has_role('ROLE_CONTENT_ADMIN')")
 */
class ReportController extends Controller implements PaginatorAwareInterface
{

    use PaginatorTrait;

    /**
     * List bad dates of publication
     *
     * @Route("/first_pub_date", name="report_first_pub_date", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function firstPubDateAction(Request $request, EntityManagerInterface $em)
    {
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
     * @Route("/titles_fc", name="report_titles_check", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titlesFinalCheckAction(Request $request, EntityManagerInterface $em)
    {
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
     * @Route("/firms_fc", name="report_firms_check", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function firmsFinalCheckAction(Request $request, EntityManagerInterface $em)
    {
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
     * @Route("/persons_fc", name="report_person_check", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function personsFinalCheckAction(Request $request, EntityManagerInterface $em)
    {
        $dql = 'SELECT e FROM AppBundle:Person e WHERE e.finalcheck = 0 ORDER BY e.id';
        $query = $em->createQuery($dql);
        $persons = $this->paginator->paginate($query->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'persons' => $persons,
        );
    }

    /**
     * List titles that are missing source identifiers.
     *
     * @Route("/title_source_id_null", name="report_title_source_id_null", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titleSourceIdNull(Request $request, EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from('AppBundle:Title', 'e');
        $qb->innerJoin('e.titleSources', 'ts');
        $qb->where('ts.identifier is null');
        $titles = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

    /**
     * List titles that do not have sources.
     *
     * @Route("/title_without_source", name="report_title_without_source", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titleWithoutSource(Request $request, EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from('AppBundle:Title', 'e');
        $qb->addSelect('COUNT(ts) AS HIDDEN titleSources');
        $qb->leftJoin('e.titleSources', 'ts');
        $qb->groupBy('e');
        $qb->having('titleSources = 0');

        $titles = $this->paginator->paginate($qb->getQuery()->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

    /**
     * List titles before 1800 that do not have a genre.
     *
     * @Route("/title_without_genre", name="report_title_without_genre", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titleWithoutGenre(Request $request, EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from('AppBundle:Title', 'e');
        $qb->where('e.genre is null');
        $qb->andWhere('e.pubdate <= 1800');
        $titles = $this->paginator->paginate($qb->getQuery()->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

    /**
     * List titles before 1800 that do not have a volume.
     *
     * @Route("/title_without_volume", name="report_title_without_volume", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titleWithoutVolume(Request $request, EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from('AppBundle:Title', 'e');
        $qb->where('e.volumes is null');
        $qb->andWhere('e.pubdate <= 1800');
        $titles = $this->paginator->paginate($qb->getQuery()->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

    /**
     * List titles before 1800 that do not have a firm associated with them.
     *
     * @Route("/title_without_firm", name="report_title_without_firm", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titleWithoutFirm(Request $request, EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('t')->from('AppBundle:Title', 't');
        $qb->addSelect('COUNT(tfr) as HIDDEN firmroles');
        $qb->leftJoin('t.titleFirmroles', 'tfr');
        $qb->where('t.pubdate <= 1800');
        $qb->groupBy('t');
        $qb->having('firmroles = 0');

        $titles = $this->paginator->paginate($qb->getQuery()->execute(), $request->query->getInt('page', 1), 25);

        return array(
            'titles' => $titles,
        );
    }

    /**
     * Titles with ESTC IDs that don't match anything in the ESTC MARC data.
     *
     * @Route("/title_bad_estc", name="report_title_bad_estc", methods={"GET"})
     * @Template()
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return array
     */
    public function titleBadEstc(Request $request, EntityManagerInterface $em)
    {
        $source = $em->find(Source::class, 2);

        $subq = $em->createQueryBuilder();
        $subq->select('e.fieldData');
        $subq->from(EstcMarc::class, 'e');
        $subq->where("e.field = '001'");

        $qb = $em->createQueryBuilder();
        $qb->select('ts');
        $qb->from(TitleSource::class, 'ts');
        $qb->where('ts.source = :source');
        $qb->andWhere($qb->expr()->notIn("ts.identifier", $subq->getDQL()));
        $qb->orderBy('ts.title');
        $qb->setParameter('source', $source);

        $titleSources = $this->paginator->paginate($qb->getQuery(), $request->query->getInt('page', 1), 25);
        return [
            'titleSources' => $titleSources,
        ];
    }
}
