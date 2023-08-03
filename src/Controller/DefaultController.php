<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Post;
use App\Entity\PostCategory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Default controller for the home page.
 */
class DefaultController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Home page.
     *
     * @throws NonUniqueResultException
     *
     * @return array<string,mixed>
     */
    #[Route(path: '/', name: 'homepage')]
    #[Template]
    public function indexAction(EntityManagerInterface $em) {
        $pageRepo = $em->getRepository(Page::class);

        $spotlightCategories = [
            $em->find(PostCategory::class, 1),
            $em->find(PostCategory::class, 2),
            $em->find(PostCategory::class, 3),
        ];
        $qb = $em->createQueryBuilder();
        $qb->select('p');
        $qb->from(Post::class, 'p');
        $qb->where('p.category = :category');
        $qb->innerJoin('p.status', 's');
        $qb->andWhere('s.public = 1');
        $qb->orderBy('p.id', 'desc');
        $qb->setMaxResults(1);

        $spotlights = [];

        foreach ($spotlightCategories as $cat) {
            $spotlights[] = $qb->getQuery()->setParameter('category', $cat)->getOneOrNullResult();
        }

        return [
            'homepage' => $pageRepo->findHomepage(),
            'spotlights' => $spotlights,
        ];
    }

    /**
     * Privacy page.
     *
     * @return array{}
     */
    #[Route(path: '/privacy', name: 'privacy')]
    #[Template]
    public function privacyAction(Request $request) {
        return [];
    }
}
