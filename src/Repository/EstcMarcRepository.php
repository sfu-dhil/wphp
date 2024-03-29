<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EstcMarc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * EstcRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @phpstan-extends ServiceEntityRepository<EstcMarc>
 */
class EstcMarcRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, EstcMarc::class);
    }

    /**
     * Create a query to return records for the index page.
     *
     * @return \Doctrine\ORM\Query
     */
    public function indexQuery() {
        $qb = $this->createQueryBuilder('m');
        $qb->where("m.field = 'ldr'");

        return $qb->getQuery();
    }

    /**
     * Execute a search query over the 245 and 100 fields.
     *
     * @param string $q
     *
     * @return mixed
     */
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.titleId');
        $qb->addSelect('MAX(MATCH(e.fieldData) AGAINST (:q BOOLEAN)) AS HIDDEN score');
        $qb->andWhere('e.field IN (\'245\', \'100\')');
        $qb->andHaving('score > 0');
        $qb->groupBy('e.titleId');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        $matches = [];
        if (preg_match('/^"(.*?)"$/', $q, $matches)) {
            $qb->andWhere('e.fieldData LIKE :text');
            $qb->setParameter('text', '%' . $matches[1] . '%');
        }

        return $qb->getQuery()->execute();
    }

    /**
     * Run a search query over the 260 field (imprint).
     *
     * @param string $q
     *
     * @return mixed
     */
    public function imprintSearchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.titleId');
        $qb->addSelect('MAX(MATCH(e.fieldData) AGAINST (:q BOOLEAN)) AS HIDDEN score');
        $qb->andWhere('e.field = \'260\'');
        $qb->andWhere('e.subfield = \'b\'');
        $qb->andHaving('score > 0');
        $qb->groupBy('e.titleId');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        $matches = [];
        if (preg_match('/^"(.*?)"$/', $q, $matches)) {
            $qb->andWhere('e.fieldData LIKE :text');
            $qb->setParameter('text', '%' . $matches[1] . '%');
        }

        return $qb->getQuery()->execute();
    }
}
