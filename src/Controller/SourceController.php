<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Source;
use App\Entity\TitleSource;
use App\Form\SourceType;
use App\Repository\SourceRepository;
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
 * Source controller.
 *
 * @Route("/source")
 */
class SourceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Source entities.
     *
     * @Route("/", name="source_index", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function indexAction(Request $request, EntityManagerInterface $em, SourceRepository $repo) {
        $qb = $em->createQueryBuilder();
        $qb->select('IDENTITY(ts.source) as srcId, COUNT(DISTINCT(ts.title)) as cnt');
        $qb->from(TitleSource::class, 'ts');
        $qb->groupBy('ts.source');
        $qb->orderBy('ts.source');
        $counts = [];
        foreach($qb->getQuery()->getResult() as $result) {
            $counts[$result['srcId']] = $result['cnt'];
        }
        dump($counts);

        $dql = 'SELECT e FROM App:Source e ORDER BY e.name';
        $query = $em->createQuery($dql);
        $sources = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'sources' => $sources,
            'repo' => $repo,
            'counts' => $counts,
        ];
    }

    /**
     * Typeahead action for editor widgets.
     *
     * @return JsonResponse
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="source_typeahead", methods={"GET"})
     */
    public function typeaheadAction(Request $request, SourceRepository $repo) {
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
     * Creates a new Source entity.
     *
     * @Route("/new", name="source_new", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $source = new Source();
        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($source);
            $em->flush();

            $this->addFlash('success', 'The new source was created.');

            return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
        }

        return [
            'source' => $source,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Source entity.
     *
     * @Route("/{id}", name="source_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function showAction(Request $request, Source $source, EntityManagerInterface $em) {
        $dql = 'SELECT t FROM App:Title t INNER JOIN t.titleSources ts WHERE ts.source = :source ORDER BY t.title';
        $query = $em->createQuery($dql);
        $query->setParameter('source', $source);
        $titles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'source' => $source,
            'titles' => $titles,
        ];
    }

    /**
     * Displays a form to edit an existing Source entity.
     *
     * @Route("/{id}/edit", name="source_edit", methods={"GET", "POST"})
     * @Template
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Source $source, EntityManagerInterface $em) {
        $editForm = $this->createForm(SourceType::class, $source);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The source has been updated.');

            return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
        }

        return [
            'source' => $source,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Source entity.
     *
     * @Route("/{id}/delete", name="source_delete", methods={"GET"})
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Source $source, EntityManagerInterface $em) {
        $em->remove($source);
        $em->flush();
        $this->addFlash('success', 'The source was deleted.');

        return $this->redirectToRoute('source_index');
    }
}
