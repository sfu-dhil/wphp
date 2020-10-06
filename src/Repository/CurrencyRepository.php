<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('currency')
            ->orderBy('currency.id')
            ->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Collection|Currency[]
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('currency');
        $qb->andWhere('currency.name LIKE :q');
        $qb->orderBy('currency.name', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $q
     *
     * @return Collection|Currency[]
     */
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('currency');
        $qb->andWhere('currency.name LIKE :q');
        $qb->orderBy('currency.name', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

}
