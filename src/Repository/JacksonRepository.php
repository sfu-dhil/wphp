<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Jackson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * JacksonRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JacksonRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Jackson::class);
    }

    public function indexQuery() : Query {
        $qb = $this->createQueryBuilder('e');
        $qb->orderBy('e.id');

        return $qb->getQuery();
    }

    /**
     * Do a name search on author and title for a typeahead query.
     *
     * @return mixed
     */
    public function searchQuery(string $q) {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect('MATCH (e.author, e.title) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->where('MATCH (e.author, e.title) AGAINST(:q BOOLEAN) > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
