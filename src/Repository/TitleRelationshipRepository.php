<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TitleRelationship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UtilBundle\Repository\TermRepository;

/**
 * @method TitleRelationship|null find($id, $lockMode = null, $lockVersion = null)
 * @method TitleRelationship|null findOneBy(array $criteria, array $orderBy = null)
 * @method TitleRelationship[]    findAll()
 * @method TitleRelationship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TitleRelationshipRepository extends TermRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TitleRelationship::class);
    }

}
