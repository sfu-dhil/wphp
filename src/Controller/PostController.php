<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\MediaBundle\Controller\PdfControllerTrait;
use Nines\MediaBundle\Entity\Pdf;
use Nines\MediaBundle\Service\PdfManager;
use Nines\UserBundle\Entity\User;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/post")
 */
class PostController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;
    use PdfControllerTrait;

    /**
     * @Route("/", name="nines_blog_post_index", methods={"GET"})
     */
    public function index(Request $request, PostRepository $postRepository) : Response {
        $query = $postRepository->indexQuery($this->isGranted('ROLE_USER'));
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return $this->render('post/index.html.twig', [
            'posts' => $this->paginator->paginate($query, $page, $pageSize),
        ]);
    }

    /**
     * @Route("/search", name="nines_blog_post_search", methods={"GET"})
     */
    public function search(Request $request, PostRepository $postRepository) : Response {
        $q = $request->query->get('q');
        if ($q) {
            $query = $postRepository->searchQuery($q);
            $posts = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), [
                'wrap-queries' => true,
            ]);
        } else {
            $posts = [];
        }

        return $this->render('post/search.html.twig', [
            'posts' => $posts,
            'q' => $q,
        ]);
    }

    /**
     * @Route("/new", name="nines_blog_post_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function new(Request $request) : Response {
        /** @var User $user */
        $user = $this->getUser();
        $post = new Post();
        $post->setUser($user);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'The new post has been saved.');

            return $this->redirectToRoute('nines_blog_post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new_popup", name="nines_blog_post_new_popup", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function new_popup(Request $request) : Response {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="nines_blog_post_show", methods={"GET"})
     */
    public function show(Post $post) : Response {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="nines_blog_post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Post $post) : Response {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated post has been saved.');

            return $this->redirectToRoute('nines_blog_post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="nines_blog_post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post) : RedirectResponse {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            $this->addFlash('success', 'The post has been deleted.');
        } else {
            $this->addFlash('warning', 'The security token was not valid.');
        }

        return $this->redirectToRoute('nines_blog_post_index');
    }

    /**
     * @Route("/{id}/new_pdf", name="post_new_pdf", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @throws Exception
     */
    public function newPdf(Request $request, EntityManagerInterface $em, Post $post) : Response {
        $context = $this->newPdfAction($request, $em, $post, 'nines_blog_post_show');
        if ($context instanceof RedirectResponse) {
            return $context;
        }

        return $this->render('post/new_pdf.html.twig', array_merge($context, [
            'post' => $post,
        ]));
    }

    /**
     * @Route("/{id}/edit_pdf/{pdf_id}", name="post_edit_pdf", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @ParamConverter("pdf", options={"id": "pdf_id"})
     *
     * @throws Exception
     */
    public function editPdf(Request $request, EntityManagerInterface $em, Post $post, Pdf $pdf, PdfManager $fileUploader) : Response {
        $context = $this->editPdfAction($request, $em, $post, $pdf, 'nines_blog_post_show');
        if ($context instanceof RedirectResponse) {
            return $context;
        }

        return $this->render('post/edit_pdf.html.twig', array_merge($context, [
            'post' => $post,
        ]));
    }

    /**
     * @Route("/{id}/delete_pdf/{pdf_id}", name="post_delete_pdf", methods={"DELETE"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @ParamConverter("pdf", options={"id": "pdf_id"})
     *
     * @throws Exception
     */
    public function deletePdf(Request $request, EntityManagerInterface $em, Post $post, Pdf $pdf) : RedirectResponse {
        return $this->deletePdfAction($request, $em, $post, $pdf, 'nines_blog_post_show');
    }
}
