<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PostCategory;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UtilBundle\Repository\TermRepository;

/**
 * @method null|PostCategory find($id, $lockMode = null, $lockVersion = null)
 * @method PostCategory[] findAll()
 * @method PostCategory[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method null|PostCategory findOneBy(array $criteria, array $orderBy = null)
 */
class PostCategoryRepository extends TermRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, PostCategory::class);
    }
}
