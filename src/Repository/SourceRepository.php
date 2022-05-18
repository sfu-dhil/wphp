<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Source;
use App\Entity\Title;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @phpstan-extends ServiceEntityRepository<Source>
 */
class SourceRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Source::class);
    }

    /**
     * Execute a name search for a typeahead widget.
     *
     * @param string $q
     *
     * @return mixed
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.name LIKE :q');
        $qb->orderBy('e.name');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * Count the titles in a source.
     *
     * @todo this code is out of date. Titles no longer have source2, source3 etc fields.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function countTitles(Source $source) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(title.id)');
        $qb->where('title.source = :source');
        $qb->orWhere('title.source2 = :source');
        $qb->orWhere('title.source3 = :source');
        $qb->setParameter('source', $source);
        $qb->from(Title::class, 'title');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
