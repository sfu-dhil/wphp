<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UserBundle\Entity\User;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/blog/page')]
class PageController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'nines_blog_page_index', methods: ['GET'])]
    public function index(Request $request, PageRepository $pageRepository) : Response {
        $query = $pageRepository->indexQuery($this->isGranted('ROLE_USER'));
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return $this->render('page/index.html.twig', [
            'pages' => $this->paginator->paginate($query, $page, $pageSize),
        ]);
    }

    #[Route(path: '/search', name: 'nines_blog_page_search', methods: ['GET'])]
    public function search(Request $request, PageRepository $pageRepository) : Response {
        $q = $request->query->get('q');
        if ($q) {
            $query = $pageRepository->searchQuery($q, $this->isGranted('ROLE_USER'));
            $pages = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), [
                'wrap-queries' => true,
            ]);
        } else {
            $pages = [];
        }

        return $this->render('page/search.html.twig', [
            'pages' => $pages,
            'q' => $q,
        ]);
    }

    #[Route(path: '/new', name: 'nines_blog_page_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function new(EntityManagerInterface $entityManager, Request $request, PageRepository $repo) : Response {
        /** @var User $user */
        $user = $this->getUser();
        $page = new Page();
        $page->setUser($user);

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($page->getHomepage()) {
                // make sure all other pages are NOT the home page.
                $repo->clearHomepages($page);
            }
            $entityManager->persist($page);
            $entityManager->flush();
            $this->addFlash('success', 'The new page has been saved.');

            return $this->redirectToRoute('nines_blog_page_show', ['id' => $page->getId()]);
        }

        return $this->render('page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/sort', name: 'nines_blog_page_sort', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_BLOG_ADMIN')]
    public function sortAction(Request $request, EntityManagerInterface $em, PageRepository $repo) : Response {
        if ('POST' === $request->getMethod()) {
            $order = $request->request->get('order');
            $list = explode(',', $order);

            for ($i = 0; $i < count($list); $i++) {
                $page = $repo->find($list[$i]);
                $page->setWeight($i + 1);
            }
            $em->flush();
            $this->addFlash('success', 'The pages have been ordered.');

            return $this->redirectToRoute('nines_blog_page_index');
        }

        $pages = $repo->findBy(
            [],
            ['weight' => 'ASC', 'title' => 'ASC'],
        );

        return $this->render('page/sort.html.twig', [
            'pages' => $pages,
        ]);
    }

    #[Route(path: '/{id}', name: 'nines_blog_page_show', methods: ['GET'])]
    public function show(Page $page) : Response {
        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }

    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Route(path: '/{id}/edit', name: 'nines_blog_page_edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $entityManager, Request $request, Page $page, PageRepository $repo) : Response {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($page->getHomepage()) {
                // make sure all other pages are NOT the home page.
                $repo->clearHomepages($page);
            }

            $entityManager->flush();
            $this->addFlash('success', 'The updated page has been saved.');

            return $this->redirectToRoute('nines_blog_page_show', ['id' => $page->getId()]);
        }

        return $this->render('page/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Route(path: '/{id}', name: 'nines_blog_page_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Request $request, Page $page) : RedirectResponse {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $entityManager->remove($page);
            $entityManager->flush();
            $this->addFlash('success', 'The page has been deleted.');
        } else {
            $this->addFlash('warning', 'The security token was not valid.');
        }

        return $this->redirectToRoute('nines_blog_page_index');
    }
}
