<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Format;
use App\Entity\Title;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UserBundle\Entity\User;

/**
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @phpstan-extends ServiceEntityRepository<Format>
 */
class FormatRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Format::class);
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
     * Count the titles in a format.
     *
     * @param ?User $user
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function countTitles(Format $format, ?User $user = null) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(title.id)');
        $qb->andWhere('title.format = :format');
        if ( ! $user) {
            $qb->andWhere('title.finalattempt = 1 OR title.finalcheck = 1');
        }
        $qb->setParameter('format', $format);
        $qb->from(Title::class, 'title');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
