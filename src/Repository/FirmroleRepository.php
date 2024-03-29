<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Firmrole;
use App\Entity\TitleFirmrole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * FirmroleRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @phpstan-extends ServiceEntityRepository<Firmrole>
 */
class FirmroleRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Firmrole::class);
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
     * Count the firms in a given role.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function countFirms(Firmrole $firmrole) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(1)');
        $qb->andWhere('titlefirmrole.firmrole = :firmrole');
        $qb->setParameter('firmrole', $firmrole);
        $qb->from(TitleFirmrole::class, 'titlefirmrole');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
