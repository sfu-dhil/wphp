<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Genre controller.
 *
 * @Route("/genre")
 */
class GenreController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Genre entities.
     *
     * @Route("/", name="genre_index", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function indexAction(Request $request, GenreRepository $repo, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Genre e ORDER BY e.name';
        $query = $em->createQuery($dql);
        $genres = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'genres' => $genres,
            'repo' => $repo,
        ];
    }

    /**
     * Typeahead action for an editor widget.
     *
     * @return JsonResponse
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="genre_typeahead", methods={"GET"})
     */
    public function typeaheadAction(Request $request, GenreRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Genre entity.
     *
     * @Route("/new", name="genre_new", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Template
     *
     * @return array<string,mixed>|RedirectResponse
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'The new genre was created.');

            return $this->redirectToRoute('genre_show', ['id' => $genre->getId()]);
        }

        return [
            'genre' => $genre,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Genre entity.
     *
     * @Route("/{id}", name="genre_show", methods={"GET"})
     * @Template
     *
     * @return array<string,mixed>     */
    public function showAction(Request $request, Genre $genre, EntityManagerInterface $em) {
        $dql = 'SELECT t FROM App:Title t WHERE :genre MEMBER OF t.genres';
        if (null === $this->getUser()) {
            $dql .= ' AND (t.finalcheck = 1 OR t.finalattempt = 1)';
        }
        $dql .= ' ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('genre', $genre);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'genre' => $genre,
            'titles' => $titles,
        ];
    }

    /**
     * Displays a form to edit an existing Genre entity.
     *
     * @Route("/{id}/edit", name="genre_edit", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Template
     *
     * @return array<string,mixed>|RedirectResponse
     */
    public function editAction(Request $request, Genre $genre, EntityManagerInterface $em) {
        $editForm = $this->createForm(GenreType::class, $genre);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The genre has been updated.');

            return $this->redirectToRoute('genre_show', ['id' => $genre->getId()]);
        }

        return [
            'genre' => $genre,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Genre entity.
     *
     * @Route("/{id}/delete", name="genre_delete", methods={"GET"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Genre $genre, EntityManagerInterface $em) {
        $em->remove($genre);
        $em->flush();
        $this->addFlash('success', 'The genre was deleted.');

        return $this->redirectToRoute('genre_index');
    }
}
