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
            $em->find(PostCategory::class, 9),
            $em->find(PostCategory::class, 10),
        ];
        $qb = $em->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p')
            ->where('p.category IN (:category)')
            ->innerJoin('p.status', 's')
            ->andWhere('s.public = 1')
            ->orderBy('p.id', 'desc')
            ->setParameter('category', $spotlightCategories)
            ->setMaxResults(3);

        return [
            'homepage' => $pageRepo->findHomepage(),
            'spotlights' => $qb->getQuery()->getResult(),
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
