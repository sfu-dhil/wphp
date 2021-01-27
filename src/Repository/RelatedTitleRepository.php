<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RelatedTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RelatedTitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method RelatedTitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method RelatedTitle[]    findAll()
 * @method RelatedTitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelatedTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RelatedTitle::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('relatedTitle')
            ->orderBy('relatedTitle.id')
            ->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Collection|RelatedTitle[]
     */
    public function typeaheadQuery($q) {
        throw new \RuntimeException("Not implemented yet.");
        $qb = $this->createQueryBuilder('relatedTitle');
        $qb->andWhere('relatedTitle.column LIKE :q');
        $qb->orderBy('relatedTitle.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    
}
