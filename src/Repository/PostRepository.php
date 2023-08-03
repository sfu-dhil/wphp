<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Post find($id, $lockMode = null, $lockVersion = null)
 * @method Post[] findAll()
 * @method Post[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method null|Post findOneBy(array $criteria, array $orderBy = null)
 *
 * @phpstan-extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Post::class);
    }

    public function indexQuery(?bool $includePrivate = false, ?int $limit = 0) : Query {
        $qb = $this->createQueryBuilder('post');
        if ( ! $includePrivate) {
            $qb->innerJoin('post.status', 'status');
            $qb->andWhere('status.public = true');
        }
        $qb->orderBy('post.id', 'desc');
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }

    public function categoryQuery(PostCategory $category, ?bool $includePrivate = false) : Query {
        $qb = $this->createQueryBuilder('post');
        if ( ! $includePrivate) {
            $qb->innerJoin('post.status', 'status');
            $qb->andWhere('status.public = true');
        }
        $qb->andWhere('post.category = :category');
        $qb->setParameter('category', $category);
        $qb->orderBy('post.id', 'desc');

        return $qb->getQuery();
    }

    public function searchQuery(string $q, ?bool $includePrivate = false) : Query {
        $qb = $this->createQueryBuilder('post');
        $qb->addSelect('MATCH (post.title, post.searchable) AGAINST(:q BOOLEAN) as HIDDEN score');
        if ( ! $includePrivate) {
            $qb->innerJoin('post.status', 'status');
            $qb->andWhere('status.public = true');
        }
        $qb->andHaving('score > 0');
        $qb->orderBy('score', 'DESC');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }

    public function statusQuery(PostStatus $postStatus, ?bool $includePrivate) : Query {
        $qb = $this->createQueryBuilder('post');
        $qb->andWhere('post.status = :status');
        $qb->setParameter('status', $postStatus);
        $qb->orderBy('post.id', 'desc');

        return $qb->getQuery();
    }
}
