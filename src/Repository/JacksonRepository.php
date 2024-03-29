<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Jackson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * JacksonRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @phpstan-extends ServiceEntityRepository<Jackson>
 */
class JacksonRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Jackson::class);
    }

    /**
     * Do a name search on author and title for a typeahead query.
     *
     * @param string $q
     *
     * @return mixed
     */
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect('MATCH (e.author, e.title) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->where('MATCH (e.author, e.title) AGAINST(:q BOOLEAN) > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
