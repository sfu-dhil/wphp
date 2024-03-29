<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Geonames;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * PersonRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @phpstan-extends ServiceEntityRepository<Geonames>
 */
class GeonamesRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Geonames::class);
    }

    /**
     * Do a name search for a typeahead query.
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
     * Run a title search.
     *
     * @param string $q
     *
     * @return mixed
     */
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.name LIKE :q');
        $qb->orderBy('e.name');
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery()->execute();
    }
}
